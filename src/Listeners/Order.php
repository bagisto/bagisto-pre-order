<?php

namespace Webkul\PreOrder\Listeners;

use Cart as CartFacade;
use Illuminate\Support\Str;
use Webkul\PreOrder\Repositories\PreOrderItemRepository;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * PreOrderItemRepository object
     *
     * @var Object
    */
    protected $preOrderItemRepository;

    /**
     * Create a new Order event listener instance.
     *
     * @param  Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * After sales order creation, add entry to pre_order_items order table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        foreach ($order->items()->get() as $item) {
            if (isset($item->additional['pre_order_payment'])) {
                $preOrderItem = $this->preOrderItemRepository->findOneByField('order_item_id', $item->additional['order_item_id']);

                $this->preOrderItemRepository->update([
                    'status' => 'processing',
                    'payment_order_item_id' => $item->id
                ], $preOrderItem->id);
            } else {
                if ($item->type == 'configurable') {
                    if ($item->child->product->getTypeInstance()->totalQuantity() > -1 || ! $item->child->product->allow_preorder) {
                        continue;
                    }
                } else {
                    if ($item->product->getTypeInstance()->totalQuantity() > -1 || ! $item->product->allow_preorder) {
                        continue;
                    }
                }

                if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial') {
                    $preOrderType = 'partial';

                    if (is_null(core()->getConfigData('preorder.settings.general.percent'))) {
                        $preOrderPercentage =  0;
                    } else {
                        $preOrderPercentage = core()->getConfigData('preorder.settings.general.percent');
                    }
                } else {
                    $preOrderType = 'complete';

                    $preOrderPercentage = 100;
                }

                $productPrice = $item->type == 'configurable'
                        ? $item->child->product->getTypeInstance()->getMinimalPrice()
                        : $item->product->getTypeInstance()->getMinimalPrice();

                $this->preOrderItemRepository->create([
                        'preorder_type' => $preOrderType,
                        'preorder_percent' => $preOrderPercentage,
                        'status' => 'pending',
                        'paid_amount' => $item->total,
                        'base_paid_amount' => $item->base_total,
                        'base_remaining_amount' => ($productPrice * $item->qty_ordered) - $item->base_total,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'token' => Str::random(32)
                    ]);
            }
        }
    }
}
