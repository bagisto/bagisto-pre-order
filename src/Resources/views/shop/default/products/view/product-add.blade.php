{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}

<div class="add-to-buttons">
    @if ($product->type != 'configurable' && $product->type == 'simple')
        @if ($product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))
            <button type="submit" class="btn btn-lg btn-primary addtocart" style="margin-left: 21%;">
                {{ __('preorder::app.shop.products.preorder') }}
            </button>
        @else
            @include ('shop::products.add-to-cart', ['product' => $product])

            @include ('shop::products.buy-now')
        @endif
    @else
        @include ('shop::products.add-to-cart', ['product' => $product])

        @include ('shop::products.buy-now')

        <button type="submit" class="btn btn-lg btn-primary pre-order-btn" style="margin-left: 36%; display: none;">
            {{ __('preorder::app.shop.products.preorder') }}
        </button>
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.product-add.after', ['product' => $product]) !!}