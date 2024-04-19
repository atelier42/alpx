{*
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2023 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<h4 class="text-center mt-4 instagram-feed-title">{l s='Instagram posts' mod='pronesis_instagram'}</h4>
<div class="instagram-feed my-4">
  <div id="instagram-feed-carousel" class="instagram-feed-carousel">
    {foreach $images as $image}
    <div class="instagram-feed-carousel-item">
          <a href="{if $image.media_type == 'VIDEO'}{$image.thumbnail_url}{elseif $image.media_type == 'IMAGE'}{$image.media_url}{/if}" rel="instagram-gallery">
            <img data-lazy="{if $image.media_type == 'VIDEO'}{$image.thumbnail_url}{elseif $image.media_type == 'IMAGE'}{$image.thumbnail}{/if}" class="img-fluid" alt="{if isset($image.caption)}{$image.caption}{/if}" />
          </a>
          <figcaption>
            <h5 class="d-none d-sm-block">{if isset($image.caption)}{$image.caption|truncate:30:"...":true}{/if}<small> ({$image.timestamp|date_format:"%A %e %B %Y"}) - <a href="{$image.permalink}" target="_blank" rel="nofollow noopener">{l s='Go to Instagram' mod='pronesis_instagram'}</a></small></h5>
          </figcaption>
        </div>
    {/foreach}          
  </div>
</div>
