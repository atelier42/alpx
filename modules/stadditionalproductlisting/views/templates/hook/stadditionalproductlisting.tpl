{*
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*}

{if $products}
{if $width || $color}
<style>
{if $width}
section.stadditionalproductlisting_{$id_additionalproductlisting|intval}{
    width: {$width|intval}px;
}
{/if}
{if $color}
section.stadditionalproductlisting_{$id_additionalproductlisting|intval} .owl-carousel .owl-nav button.owl-next,
section.stadditionalproductlisting_{$id_additionalproductlisting|intval} .owl-carousel .owl-nav button.owl-prev,
section.stadditionalproductlisting_{$id_additionalproductlisting|intval} .owl-carousel button.owl-dot.active {
    background: {$color|escape:'htmlall':'UTF-8'};
}
{/if}
</style>
{/if}
<section id="products" class="stadditionalproductlisting_{$id_additionalproductlisting|intval} clearfix">
  {if $name}
  <h2 class="h1 products-section-title">{$name|escape:'htmlall':'UTF-8'}</h2>
  {/if}
  {if $description}
  <div class="additional-product-listing-description">{$description nofilter}</div>
  {/if}
  {if $is_carousel}
      <div class="products owl-carousel owl-theme" data-pause="{$pause|escape:'htmlall':'UTF-8'}"
      data-nav="{$nav|escape:'htmlall':'UTF-8'}" data-auto="{$auto|escape:'htmlall':'UTF-8'}" data-dots="{$pager|escape:'htmlall':'UTF-8'}">
          {foreach from=$products item="product"}
              <div class="item">
              {include file='catalog/_partials/miniatures/product.tpl' product=$product}
              </div>
          {/foreach}
      </div>
  {else}
      <div class="products">
          {foreach from=$products item="product"}
              {include file='catalog/_partials/miniatures/product.tpl' product=$product}
          {/foreach}
      </div>
  {/if}
</section>
{/if}
