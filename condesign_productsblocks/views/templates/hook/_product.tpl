<div class="con-pb__product">
    <div class="con-pb__product-left">
        {if $product.cover}
            <a href="{$product.url}" class="con-pb__product-image">
                <img
                        class="img-fluid"
                        src="{$product.cover.bySize.small_default.url}"
                        alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
                        data-full-size-image-url="{$product.cover.large.url}"
                />
            </a>
        {else}
            <a href="{$product.url}" class="con-pb__product-image">
                <img class="img-fluid" src="{$urls.no_picture_image.bySize.small_default.url}" />
            </a>
        {/if}
        <div class="con-pb__product-title">
            <a href="{$product.url}" content="{$product.url}">{$product.name|truncate:60:'...'}</a>
        </div>
    </div>
    <div class="con-pb__product-right">
        {if $product.show_price}
            <div class="con-pb__product-price">
                {hook h='displayProductPriceBlock' product=$product type="before_price"}

                <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">{$product.price}</span>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="invisible">
                    <meta itemprop="priceCurrency" content="{$currency.iso_code}" />
                    <meta itemprop="price" content="{$product.price_amount}" />
                </div>

                {hook h='displayProductPriceBlock' product=$product type='unit_price'}
                {hook h='displayProductPriceBlock' product=$product type='weight'}

{*                {if $product.has_discount}
                    {hook h='displayProductPriceBlock' product=$product type="old_price"}
                    <span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
                {/if}*}
            </div>
        {/if}
        <div class="con-pb__product-actions">
            <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh con-pb__product-form">
                <input type="hidden" name="token" value="{$static_token}">
                <input type="hidden" name="id_product" value="{$product.id}">
                <input type="hidden" name="id_customization" value="0">
                <input type="hidden" name="id_product_attribute" value="0">
                <input class="form-control con-pb__product-qty"onkeyup=enforceMinMax(this) type="number" value="1" min="1" max="{$product.quantity}" name="qty">
                <button class="con-pb__product-btn" data-button-action="add-to-cart" type="submit">
                    <i class="material-icons">shopping_cart</i>
                </button>

            </form>
        </div>
    </div>
</div>