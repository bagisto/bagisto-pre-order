{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

    <div class="mx-0 no-padding">
        @if (isset($showCompare) && $showCompare)
            <compare-component
                @auth('customer')
                    customer="true"
                @endif

                @guest('customer')
                    customer="false"
                @endif

                slug="{{ $product->url_key }}"
                product-id="{{ $product->id }}"
                add-tooltip="{{ __('velocity::app.customer.compare.add-tooltip') }}"
                style=".cd-quick-view .product-actions .compare-icon, .cd-quick-view .product-actions .wishlist-icon {
                            margin-top: 30px!important;
                        }"
            ></compare-component>
        @endif

        @if (! (isset($showWishlist) && !$showWishlist))
            @include('shop::products.wishlist', [
                'addClass' => $addWishlistClass ?? ''
            ])
        @endif

        <div class="add-to-cart-btn pl0">
            @if (isset($form) && !$form)
                @if ($product->type != "configurable" && $product->type == 'simple' && $product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))
                    @if (core()->getConfigData('preorder.settings.general.percent'))
                        @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
                            <p style="font-size: 14px;">{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
                        @endif
                    @endif
                @endif

                @if ($product->type == "configurable")
                    <button
                    type="submit"
                    {{ ! $product->isSaleable() ? 'disabled' : '' }}
                    class="theme-btn {{ $addToCartBtnClass ?? '' }}">

                    @if (! (isset($showCartIcon) && !$showCartIcon))
                        <i class="material-icons text-down-3">shopping_cart</i>
                    @endif

                        {{ __('shop::app.products.add-to-cart') }}
                    </button>
                @else
                    @if ($product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder') && $product->type == 'simple')
                        <add-to-cart
                            form="true"
                            csrf-token='{{ csrf_token() }}'
                            product-flat-id="{{ $product->id }}"
                            product-id="{{ $product->product_id }}"
                            reload-page="{{ $reloadPage ?? false }}"
                            move-to-cart="{{ $moveToCart ?? false }}"
                            add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                            show-cart-icon="{{ $showCartIcon ?? false }}"
                            btn-text="{{ __('preorder::app.shop.products.preorder') }}">
                        </add-to-cart>
                    @else
                        <button
                        type="submit"
                        {{ ! $product->isSaleable() ? 'disabled' : '' }}
                        class="theme-btn {{ $addToCartBtnClass ?? '' }}">

                        @if (! (isset($showCartIcon) && !$showCartIcon))
                            <i class="material-icons text-down-3">shopping_cart</i>
                        @endif

                            {{ __('shop::app.products.add-to-cart') }}
                        </button>
                    @endif
                @endif
            @elseif(isset($addToCartForm) && !$addToCartForm)
                <form
                    method="POST"
                    action="{{ route('cart.add', $product->product_id) }}">

                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="quantity" value="1">

                    @if ($product->type != "configurable" && $product->type == 'simple' && $product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))

                        @if (core()->getConfigData('preorder.settings.general.percent'))
                            @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
                                <p style="font-size: 14px;">{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
                            @endif
                        @endif
                    @endif

                    @if ($product->type == "configurable")
                        <button
                            type="submit"
                            {{ ! $product->isSaleable() ? 'disabled' : '' }}
                            class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                            @if (! (isset($showCartIcon) && !$showCartIcon))
                                <i class="material-icons text-down-3">shopping_cart</i>
                            @endif

                            <span class="fs14 fw6 text-uppercase text-up-4">
                                {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}
                            </span>
                        </button>
                    @else
                        @if ($product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder') && $product->type == 'simple')
                            <add-to-cart
                                form="true"
                                csrf-token='{{ csrf_token() }}'
                                product-flat-id="{{ $product->id }}"
                                product-id="{{ $product->product_id }}"
                                reload-page="{{ $reloadPage ?? false }}"
                                move-to-cart="{{ $moveToCart ?? false }}"
                                add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                                show-cart-icon="{{ $showCartIcon ?? false }}"
                                btn-text="{{ __('preorder::app.shop.products.preorder') }}">
                            </add-to-cart>
                        @else
                            <button
                            type="submit"
                            {{ ! $product->isSaleable() ? 'disabled' : '' }}
                            class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                                @if (! (isset($showCartIcon) && !$showCartIcon))
                                    <i class="material-icons text-down-3">shopping_cart</i>
                                @endif

                                <span class="fs14 fw6 text-uppercase text-up-4">
                                    {{ $btnText ?? __('shop::app.products.add-to-cart') }}
                                </span>
                            </button>
                        @endif
                    @endif
                </form>
            @else
                @if ($product->type != "configurable" && $product->type == 'simple' && $product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))

                    @if (core()->getConfigData('preorder.settings.general.percent'))
                        @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
                            <p style="font-size: 14px;">{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
                        @endif
                    @endif
                @endif

                @if ($product->type == "configurable")

                    @if ($product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder'))
                        @php
                            $count = 0;
                            foreach($product->variants as $variant) {
                                if ($variant->allow_preorder
                                    && $variant->totalQuantity() < 1) {
                                    $count++;
                                }

                                if ($variant->totalQuantity() > 0) {
                                    $count = 0;
                                    break;
                                }
                            }
                        @endphp

                        @if($count > 0)
                            <add-to-cart
                                form="true"
                                csrf-token='{{ csrf_token() }}'
                                product-flat-id="{{ $product->id }}"
                                product-id="{{ $product->product_id }}"
                                reload-page="{{ $reloadPage ?? false }}"
                                move-to-cart="{{ $moveToCart ?? false }}"
                                wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                                add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                                is-enable={{'true' }}
                                show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                                btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('preorder::app.shop.products.preorder') }}">
                            </add-to-cart>
                        @else
                            <add-to-cart
                                form="true"
                                csrf-token='{{ csrf_token() }}'
                                product-flat-id="{{ $product->id }}"
                                product-id="{{ $product->product_id }}"
                                reload-page="{{ $reloadPage ?? false }}"
                                move-to-cart="{{ $moveToCart ?? false }}"
                                wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                                add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                                is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                                show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                                btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                            </add-to-cart>
                        @endif
                    @else
                        <add-to-cart
                            form="true"
                            csrf-token='{{ csrf_token() }}'
                            product-flat-id="{{ $product->id }}"
                            product-id="{{ $product->product_id }}"
                            reload-page="{{ $reloadPage ?? false }}"
                            move-to-cart="{{ $moveToCart ?? false }}"
                            wishlist-move-route="{{ $wishlistMoveRoute ?? false }}"
                            add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                            is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                            show-cart-icon={{ ! (isset($showCartIcon) && ! $showCartIcon) }}
                            btn-text="{{ (! isset($moveToCart) && $product->type == 'booking') ?  __('shop::app.products.book-now') : $btnText ?? __('shop::app.products.add-to-cart') }}">
                        </add-to-cart>
                    @endif


                @else
                    @if ($product->totalQuantity() < 1 && $product->allow_preorder && core()->getConfigData('preorder.settings.general.enable_preorder') && $product->type == 'simple')
                        <add-to-cart
                            form="true"
                            csrf-token='{{ csrf_token() }}'
                            product-flat-id="{{ $product->id }}"
                            product-id="{{ $product->product_id }}"
                            reload-page="{{ $reloadPage ?? false }}"
                            move-to-cart="{{ $moveToCart ?? false }}"
                            add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                            show-cart-icon="{{ $showCartIcon ?? false }}"
                            btn-text="{{ __('preorder::app.shop.products.preorder') }}">
                        </add-to-cart>
                    @else
                        <add-to-cart
                            form="true"
                            csrf-token='{{ csrf_token() }}'
                            product-flat-id="{{ $product->id }}"
                            product-id="{{ $product->product_id }}"
                            reload-page="{{ $reloadPage ?? false }}"
                            move-to-cart="{{ $moveToCart ?? false }}"
                            add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                            is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                            show-cart-icon={{ !(isset($showCartIcon) && !$showCartIcon) }}
                            btn-text="{{ $btnText ?? __('shop::app.products.add-to-cart') }}">
                        </add-to-cart>
                    @endif
                @endif
            @endif
        </div>
    </div>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}