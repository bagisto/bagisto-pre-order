{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

@php $mainProduct = $product->product; @endphp


@if ($mainProduct->type == 'simple')

    @if ($mainProduct->totalQuantity() < 1
        && $product->allow_preorder
        && core()->getConfigData('preorder.settings.general.enable_preorder')
        )

        <div class="col-12 availability">
            <button type="button" class="active disable-box-shadow">
                {{ __('shop::app.products.in-stock') }}
            </button>
        </div>
    @else
        <div class="col-12 availability">
            <button
                type="button"
                class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
                    @if ( $product->haveSufficientQuantity(1) === true )
                        {{ __('shop::app.products.in-stock') }}
                    @elseif ( $product->haveSufficientQuantity(1) > 0 )
                        {{ __('shop::app.products.available-for-order') }}
                    @else
                        {{ __('shop::app.products.out-of-stock') }}
                    @endif
            </button>
        </div>
    @endif
@else
    <div class="col-12 availability">
        <button
            type="button"
            class="{{! $product->haveSufficientQuantity(1) ? '' : 'active' }} disable-box-shadow">
                @if ( $product->haveSufficientQuantity(1) === true )
                    {{ __('shop::app.products.in-stock') }}
                @elseif ( $product->haveSufficientQuantity(1) > 0 )
                    {{ __('shop::app.products.available-for-order') }}
                @else
                    {{ __('shop::app.products.out-of-stock') }}
                @endif
        </button>
    </div>
@endif


{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}