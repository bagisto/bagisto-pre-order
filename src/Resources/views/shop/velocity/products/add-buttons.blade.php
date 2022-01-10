@if ($product->type != "configurable" && $product->type == "simple"  && $product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))
    @if (core()->getConfigData('preorder.settings.general.percent'))
        @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
            <p>{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
        @endif
    @endif
@endif

@if ($product->type == "configurable")
    <div class="cart-wish-wrap">
        <a href="{{ route('cart.add', $product->product_id) }}" class="btn btn-lg btn-primary addtocart theme-btn">
            {{ __('shop::app.products.add-to-cart') }}
        </a>

        @include('shop::products.wishlist')

        @include('shop::products.compare', [
            'productId' => $product->id
        ])
    </div>
@elseif($product->type == "simple")
    <div class="cart-wish-wrap">
        <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
            @csrf
            <input type="hidden" name="product" value="{{ $product->product_id }}">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" value="false" name="is_configurable">

            @if ($product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))
                <button class="btn btn-lg btn-primary addtocart theme-btn">{{ __('preorder::app.shop.products.preorder') }}</button>
            @else
            <button class="btn btn-lg btn-primary addtocart theme-btn" {{ $product->isSaleable() ? '' : 'disabled' }}>{{ __('shop::app.products.add-to-cart') }}</button>
            @endif
        </form>

        @include('shop::products.wishlist')

        @include('shop::products.compare', [
            'productId' => $product->id
        ])
    </div>
@else
    <div class="cart-wish-wrap">
        <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
            @csrf
            <input type="hidden" name="product" value="{{ $product->product_id }}">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" value="false" name="is_configurable">
            <button class="btn btn-lg btn-primary addtocart theme-btn" {{ $product->isSaleable() ? '' : 'disabled' }}>{{ __('shop::app.products.add-to-cart') }}</button>
        </form>

        @include('shop::products.wishlist')

        @include('shop::products.compare', [
            'productId' => $product->id
        ])
    </div>
@endif