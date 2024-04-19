{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{block name='product_miniature_item'}
<div class="js-product product{if !empty($productClasses)} {$productClasses}{/if}">
  <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
    <div class="thumbnail-container">
      <div class="thumbnail-top">
        {block name='product_thumbnail'}
          {if $product.cover}
            <a href="{$product.url}" class="thumbnail product-thumbnail">
              <img
                src="{$product.cover.bySize.home_default.url}"
                alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
                loading="lazy"
                data-full-size-image-url="{$product.cover.large.url}"
                width="{$product.cover.bySize.home_default.width}"
                height="{$product.cover.bySize.home_default.height}"
              />
            </a>
          {else}
            <a href="{$product.url}" class="thumbnail product-thumbnail">
              <img
                src="{$urls.no_picture_image.bySize.home_default.url}"
                loading="lazy"
                width="{$urls.no_picture_image.bySize.home_default.width}"
                height="{$urls.no_picture_image.bySize.home_default.height}"
              />
            </a>
          {/if}
        {/block}


      </div>

      <div class="product-description">
        {block name='product_name'}
          {if $page.page_name == 'index'}
            <h3 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name}</a></h3>
          {else}
            <h2 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name}</a></h2>
          {/if}
            <div class="description-product">{$product.description_short nofilter}</div>
        {/block}

        <div class="footer-product-info">
          {block name='product_price_and_shipping'}
              {if $product.show_price}
                  <div class="product-price-and-shipping">
                      {hook h='displayProductPriceBlock' product=$product type="before_price"}


                      <span class="price {if $product.has_discount}new-price{/if}" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
                             {capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
                              {if '' !== $smarty.capture.custom_price}
                                  {$smarty.capture.custom_price nofilter}
                              {else}
                                  {$product.price}
                              {/if}
                      </span>
                      {if $product.has_discount}
                          {hook h='displayProductPriceBlock' product=$product type="old_price"}
                          {if $product.discount_type === 'percentage'}
                              <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
                          {elseif $product.discount_type === 'amount'}
                              <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
                          {/if}
                          <span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>

                      {/if}

                      {hook h='displayProductPriceBlock' product=$product type="before_price"}



                      {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                      {hook h='displayProductPriceBlock' product=$product type='weight'}
                  </div>
              {/if}
          {/block}
            <div class="footer-product-info-right">
              <div class="highlighted-informations{if !$product.main_variants} no-variants{/if}">
                  {block name='quick_view'}
                      <a class="quick-view js-quick-view" href="#" data-link-action="quickview">
                          <img class="img-fluid" src="/themes/alpx/assets/img/icon-views.svg"">
                      </a>
                  {/block}



                  {block name='product_variants'}
                      {if $product.main_variants}
                          {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                      {/if}
                  {/block}
              </div>
              <div class="product-miniature__actions">
              {if $product.add_to_cart_url && ($product.quantity > 0 || $product.allow_oosp) && !$configuration.is_catalog}
                  <form class="product-miniature__form text-right" action="{$product.add_to_cart_url}" method="post">
                      <input type="hidden" name="id_product" value="{$product.id}">
                      <input
                              type="hidden"
                              name="qty"
                              value="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity != ''}{$product.product_attribute_minimal_quantity}{else}{$product.minimal_quantity}{/if}"
                              class="form-control input-qty"
                      >
                      <button
                              class="btn btn-primary add-to-cart btn-icon"
                              data-button-action="add-to-cart"
                              type="submit"
                              {if !$product.add_to_cart_url}
                                  disabled
                              {/if}
                      >
                          <img class="img-fluid" src="/themes/alpx/assets/img/panier-white.svg" alt="{l s='Add to cart' d='Shop.Theme.Actions'}">
                      </button>
                  </form>
              {/if}
          </div>
            </div>
        </div>

        {block name='product_reviews'}
          {hook h='displayProductListReviews' product=$product}
        {/block}
      </div>

      {include file='catalog/_partials/product-flags.tpl'}
    </div>
  </article>
</div>
{/block}
