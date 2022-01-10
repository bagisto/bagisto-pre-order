<?php

namespace Webkul\PreOrder\Http\Controllers\Shop;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\PreOrder\Repositories\PreOrderItemRepository;
use Webkul\Checkout\Facades\Cart;
use Illuminate\Support\Str;


/**
 * PreOrder page controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PreOrderController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var array
    */
    protected $productRepository;

    /**
     * OrderItemRepository object
     *
     * @var array
    */
    protected $orderItemRepository;

    /**
     * OrderRepository object
     *
     * @var array
    */
    protected $orderRepository;

    /**
     * CartRuleRepository object
     *
     * @var array
    */
    protected $cartRuleRepository;

    /**
     * PreOrderItemRepository object
     *
     * @var array
    */
    protected $preOrderItemRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository       $productRepository
     * @param  Webkul\Sales\Repositories\OrderItemRepository       $orderItemRepository
     * @param  Webkul\Sales\Repositories\OrderRepository           $orderRepository
     * @param  Webkul\CartRule\Repositories\CartRuleRepository     $cartRuleRepository
     * @param  Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        OrderItemRepository $orderItemRepository,
        OrderRepository $orderRepository,
        CartRuleRepository $cartRuleRepository,
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->_config = request('_config');

        $this->productRepository = $productRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->orderRepository = $orderRepository;

        $this->cartRuleRepository = $cartRuleRepository;

        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function complete()
    {
        try {
            if (request()->route('token'))
                $preOrderItem = $this->preOrderItemRepository->findOneByField('token', request()->route('token'));
            else
                $preOrderItem = $this->preOrderItemRepository->find(request()->input('id'));

            if (! $preOrderItem)
                return abort(404);

            if ($preOrderItem->payment_order_item_id != null)
                throw new \Exception('Payment has been done for this order');
        
            $orderItem = $this->orderItemRepository->findOrFail($preOrderItem->order_item_id);
            
            $orderData = $this->orderRepository->findOrFail($preOrderItem->order_id);

            if($orderData->applied_cart_rule_ids != null) {
                $cartRuleData = $this->cartRuleRepository->findOrFail($orderData->applied_cart_rule_ids);
            }

            if (! $this->preOrderItemRepository->canBeComplete($orderItem)) {
                session()->flash('error', trans('preorder::app.shop.products.complete-preorder-error'));

                if (request()->route('token'))
                    return redirect()->route('shop.home.index');
                else
                    return back();
            }

            $data = [];

            if ($orderItem->type == 'configurable') {
             
                if(isset($cartRuleData->action_type)) {
                    $data = [ 
                        'pre_order_payment' => true,
                        'order_item_id' => $preOrderItem->order_item_id,
                        'product' => $orderItem->product_id,
                        'quantity' => $orderItem->qty_ordered,
                        'discounted_amount' => $orderItem->discount_amount,
                        'discount_type' => $cartRuleData->action_type,
                        'is_configurable' => true,
                        'selected_configurable_option' => $orderItem->child->product_id
                    ];
                } else {
                    $data = [
                        'pre_order_payment' => true,
                        'order_item_id' => $preOrderItem->order_item_id,
                        'product' => $orderItem->product_id,
                        'quantity' => $orderItem->qty_ordered,
                        'discounted_amount' => $orderItem->discount_amount,
                        'is_configurable' => true,
                        'selected_configurable_option' => $orderItem->child->product_id
                    ];
                }

                foreach ($this->productRepository->getSuperAttributes($orderItem->product) as $attribute) {
                    $data['super_attribute'][$attribute['id']] = $orderItem->child->product->{$attribute['code']};
                }
            } else {
              
                if(isset($cartRuleData->action_type)) {
                    $data = [
                        'pre_order_payment' => true,
                        'order_item_id' => $preOrderItem->order_item_id,
                        'product' => $orderItem->product_id,
                        'quantity' => $orderItem->qty_ordered,
                        'discounted_amount' => $orderItem->discount_amount,
                        'discount_type' => $cartRuleData->action_type,
                        'is_configurable' => false,
                    ];
                } else {
                    $data = [
                        'pre_order_payment' => true,
                        'order_item_id' => $preOrderItem->order_item_id,
                        'product' => $orderItem->product_id,
                        'quantity' => $orderItem->qty_ordered,
                        'discounted_amount' => $orderItem->discount_amount,
                        'is_configurable' => false,
                    ];
                }
            }

            request()->request->add($data);

            Event::dispatch('checkout.cart.add.before', $data['product']);

            $result = Cart::addProduct($data['product'], $data);

            Event::dispatch('checkout.cart.add.after', $result);

            return redirect()->route('shop.checkout.onepage.index');
        }  catch(\Exception $e) {
            if (! Str::contains($e->getMessage(), "Unknown column 'price' in 'field list'")) {
                session()->flash('error', trans($e->getMessage()));

                return redirect()->back();
            } else {
                return redirect()->route('shop.checkout.onepage.index');
            } 
        }
    }

}