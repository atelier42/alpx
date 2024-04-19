{*
* Related Products
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
*  @author    FME Modules
*  @copyright 2021 fmemodules All right reserved
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<!-- related products --> 
{if !empty($pro_array)}
{foreach $pro_array as $key=>$rule}
    {if $rule['products_list']|@count != 0}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <div id="idTab0" class="related_main_wrapper">
        <div class="{$class_name|escape:'htmlall':'UTF-8'} related_wrapper_inner clearfix">
            <div class="related_products">
              <div class="{$class_name|escape:'htmlall':'UTF-8'} title">
                <h2>{if !empty($rule.rules_title)}{$rule.rules_title|escape:'htmlall':'UTF-8'}{else}{l s='Related Products' mod='relatedproducts'}{/if}</h2>
              </div>
              <div class="posts_block">
                <div id="owl-related-products-block" class="owl-carousel owl-theme owl-related-products-block">
                {foreach item=related_product from=$rule['products_list']}
                    <div class="item clearfix">
                        {if $display_image ==1}
                          <div class="{$class_name|escape:'htmlall':'UTF-8'} rp_image">
                            <div class="{$class_name|escape:'htmlall':'UTF-8'} image_overlay">
                              <img src="{$link->getImageLink($related_product.products->link_rewrite, $related_product.id_image, $img_type)|escape:'htmlall':'UTF-8'}" alt="{$related_product.products->name|escape:'htmlall':'UTF-8'}" title="{$related_product.products->name|escape:'htmlall':'UTF-8'}">
                            </div>
                          </div>
                          {/if}
                          <div class="rp_content clearfix">
                            <h3 class="{$class_name|escape:'htmlall':'UTF-8'} related_product_name">
                              <a href="{$link->getProductLink($related_product.products->id, null, null, null, null, null, $related_product.id_combination)|escape:'htmlall':'UTF-8'}">
                              {$related_product.products->name|escape:'htmlall':'UTF-8'}
                              {if isset($related_product.attributes) AND $related_product.attributes}
                                <strong class="rp_attribute">({foreach from=$related_product.attributes item=rp_attribute}
                                {$rp_attribute.group|escape:'htmlall':'UTF-8'} - {$rp_attribute.name|escape:'htmlall':'UTF-8'}
                                  {if $rp_attribute@last}{else} | {/if}
                                  {/foreach})</strong>
                              {/if}
                              </a>
                            </h3>
                            <div class="bottom_block">
                              <div class="related_product_price">
                                <strong>{Tools::displayPrice($related_product.products->price)|escape:'htmlall':'UTF-8'}</strong>
                              </div>
                              <div class="related_checkbox">
                                <button class="btn btn-info button rp_select_button" onclick="addRelatedProducts('{$related_product.products->price|escape:'htmlall':'UTF-8'}', this, null, {$key})"> {l s='Select' mod='relatedproducts'}
                                  <input type="checkbox" data-ipa="{$related_product.id_combination|escape:'htmlall':'UTF-8'}" data-price="{$related_product.products->price|escape:'htmlall':'UTF-8'}" class="input relproductcheckbox related_check" value="{$related_product.id_related|escape:'htmlall':'UTF-8'}"/>
                                </button>
                              </div>
                            </div>
                          </div>
                      </div>
                  {/foreach}
                </div>
              </div>
            </div>
            <div class="total_price" id="relProductsPriceBlock{$key}">
                <span class="related_total">
                    <strong>{l s='Total Price' mod='relatedproducts'}</strong> : <span>0.00</span>
                    <input type="hidden" value="0.00" id="related_base_price{$key}">
                </span><hr>
                <button title="{l s='Add to cart' mod='relatedproducts'}" class="add_related_products button btn btn-primary button-medium">
                  <span>{l s='Add Selection to cart' mod='relatedproducts'}</span>
                </button>
            </div>
          </div>
        </div>
    {/if}
  {/foreach}
  {/if}