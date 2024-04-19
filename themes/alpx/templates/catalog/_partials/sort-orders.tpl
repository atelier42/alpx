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


<div class="{if !empty($listing.rendered_facets)}col-sm-6 col-xs-6{else}col-sm-12 col-xs-6{/if} col-md-12 products-sort-order dropdown">
    <span class="col-sm-12 col-md-12 hidden-sm-down sort-by">{l s='Sort by:' d='Shop.Theme.Global'}</span>
  <button
    class="btn-unstyle select-title"
    rel="nofollow"
    data-toggle="dropdown"
    aria-label="{l s='Sort by selection' d='Shop.Theme.Global'}"
    aria-haspopup="true"
    aria-expanded="false">
      {if Context::getContext()->isMobile() != 1}
        {if $listing.sort_selected}{$listing.sort_selected}{else}{l s='Select' d='Shop.Theme.Actions'}{/if}
      {else}{l s='Trier par' d='Shop.Theme.Actions'}{/if}
      <img class="icon-selector" src="../themes/alpx/assets/img/selector.svg" alt="" width="14" height="8">
  </button>
  <div class="dropdown-menu">
    {foreach from=$listing.sort_orders item=sort_order}
      <a
        rel="nofollow"
        href="{$sort_order.url}"
        class="select-list {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
      >
        {$sort_order.label}
      </a>
    {/foreach}
  </div>
</div>
