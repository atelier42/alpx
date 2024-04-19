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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{if $displayedFacets|count}
    <div id="search_filters">
        {block name='facets_title'}
            <p class="title-filter h6 hidden-sm-down">{l s='Filter By' d='Shop.Theme.Actions'}</p>
        {/block}
        <div class="block-clear-filter">
            <section id="js-active-search-filters" class="{if $activeFilters|count}active_filters{else}hide{/if}">
                {if $activeFilters|count}
                    <ul>
                        {foreach from=$activeFilters item="filter"}
                            {block name='active_filters_item'}
                                <li class="filter-block">
                                    {l s='%1$s:' d='Shop.Theme.Catalog' sprintf=[$filter.facetLabel]}
                                    {$filter.label}
                                    <a class="js-search-link" href="{$filter.nextEncodedFacetsURL}"><i class="material-icons close">&#xE5CD;</i></a>
                                </li>
                            {/block}
                        {/foreach}
                    </ul>
                {/if}
            </section>


            {block name='facets_clearall_button'}
                {if $activeFilters|count}
                    <div id="_desktop_search_filters_clear_all" class=" clear-all-wrapper">
                        <button data-search-url="{$clear_all_link}" class="btn btn-tertiary js-search-filters-clear-all">
                            {l s='Clear all' d='Shop.Theme.Actions'}
                        </button>
                    </div>
                {/if}
            {/block}

        </div>


        {foreach from=$displayedFacets item="facet"}
            <section class="facet clearfix">
{*                <p class="h6 facet-title hidden-sm-down">{$facet.label}</p>*}
                {assign var=_expand_id value=10|mt_rand:100000}
                {assign var=_collapse value=true}
                {foreach from=$facet.filters item="filter"}
                    {if $filter.active}{assign var=_collapse value=false}{/if}
                {/foreach}

                <div class="title" data-target="#facet_{$_expand_id}" data-toggle="collapse"{if !$_collapse} aria-expanded="true"{/if}>
                    <p class="h6 facet-title">{$facet.label}</p>
                    <span class="navbar-toggler collapse-icons">
                        <i class="material-icons add"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.70711 7.70711C7.31658 8.09763 6.68342 8.09763 6.29289 7.70711L0.292894 1.70711C-0.0976309 1.31658 -0.0976308 0.683418 0.292894 0.292894C0.683418 -0.0976307 1.31658 -0.0976307 1.70711 0.292894L7 5.58579L12.2929 0.292895C12.6834 -0.0976297 13.3166 -0.0976296 13.7071 0.292895C14.0976 0.683419 14.0976 1.31658 13.7071 1.70711L7.70711 7.70711Z" fill="#394061"/>
                            </svg>
                        </i>
                        <i class="material-icons remove"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                             <path fill-rule="evenodd" clip-rule="evenodd" d="M6.29289 0.292893C6.68342 -0.0976311 7.31658 -0.0976311 7.70711 0.292893L13.7071 6.29289C14.0976 6.68342 14.0976 7.31658 13.7071 7.70711C13.3166 8.09763 12.6834 8.09763 12.2929 7.70711L7 2.41421L1.70711 7.70711C1.31658 8.09763 0.683417 8.09763 0.292893 7.70711C-0.0976311 7.31658 -0.0976311 6.68342 0.292893 6.29289L6.29289 0.292893Z" fill="#394061"/>
                             </svg>
                        </i>
                    </span>
                </div>

                {if in_array($facet.widgetType, ['radio', 'checkbox'])}
                    {block name='facet_item_other'}
                        <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
                            {foreach from=$facet.filters key=filter_key item="filter"}
                                {if !$filter.displayed}
                                    {continue}
                                {/if}

                                <li>
                                    <label class="facet-label{if $filter.active} active {/if}" for="facet_input_{$_expand_id}_{$filter_key}">
                                        {if $facet.multipleSelectionAllowed}
                                            <span class="custom-checkbox">
                        <input
                                id="facet_input_{$_expand_id}_{$filter_key}"
                                class="checkmark"
                                data-search-url="{$filter.nextEncodedFacetsURL}"
                                type="checkbox"
                                {if $filter.active }checked{/if}
                        >
                        {if isset($filter.properties.color)}
                            <span class="color" style="background-color:{$filter.properties.color}"></span>
                        {elseif isset($filter.properties.texture)}
                          <span class="color texture" style="background-image:url({$filter.properties.texture})"></span>
                        {else}
                          <span {if !$js_enabled} class="ps-shown-by-js" {/if}></span>
                        {/if}
                      </span>
                                        {else}
                                            <span class="custom-radio">
                        <input
                                id="facet_input_{$_expand_id}_{$filter_key}"
                                data-search-url="{$filter.nextEncodedFacetsURL}"
                                type="radio"
                                name="filter {$facet.label}"
                                {if $filter.active }checked{/if}
                        >
                        <span {if !$js_enabled} class="ps-shown-by-js" {/if}></span>
                      </span>
                                        {/if}

                                        <a
                                                href="{$filter.nextEncodedFacetsURL}"
                                                class="_gray-darker search-link js-search-link"
                                                rel="nofollow"
                                        >
                                            {$filter.label}
                                            {if $filter.magnitude and $show_quantities}
                                                <span class="magnitude">({$filter.magnitude})</span>
                                            {/if}
                                        </a>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    {/block}

                {elseif $facet.widgetType == 'dropdown'}
                    {block name='facet_item_dropdown'}
                        <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
                            <li>
                                <div class="col-sm-12 col-xs-12 col-md-12 facet-dropdown dropdown">
                                    <a class="select-title" rel="nofollow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {$active_found = false}
                                        <span>
                      {foreach from=$facet.filters item="filter"}
                          {if $filter.active}
                              {$filter.label}
                              {if $filter.magnitude and $show_quantities}
                                  ({$filter.magnitude})
                              {/if}
                              {$active_found = true}
                          {/if}
                      {/foreach}
                                            {if !$active_found}
                                                {l s='(no filter)' d='Shop.Theme.Global'}
                                            {/if}
                    </span>
                                        <i class="material-icons float-xs-right">&#xE5C5;</i>
                                    </a>
                                    <div class="dropdown-menu">
                                        {foreach from=$facet.filters item="filter"}
                                            {if !$filter.active}
                                                <a
                                                        rel="nofollow"
                                                        href="{$filter.nextEncodedFacetsURL}"
                                                        class="select-list js-search-link"
                                                >
                                                    {$filter.label}
                                                    {if $filter.magnitude and $show_quantities}
                                                        ({$filter.magnitude})
                                                    {/if}
                                                </a>
                                            {/if}
                                        {/foreach}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    {/block}

                {elseif $facet.widgetType == 'slider'}
                    {block name='facet_item_slider'}
                        {foreach from=$facet.filters item="filter"}
                            <ul id="facet_{$_expand_id}"
                                class="faceted-slider collapse{if !$_collapse} in{/if}"
                                data-slider-min="{$facet.properties.min}"
                                data-slider-max="{$facet.properties.max}"
                                data-slider-id="{$_expand_id}"
                                data-slider-values="{$filter.value|@json_encode}"
                                data-slider-unit="{$facet.properties.unit}"
                                data-slider-label="{$facet.label}"
                                data-slider-specifications="{$facet.properties.specifications|@json_encode}"
                                data-slider-encoded-url="{$filter.nextEncodedFacetsURL}"
                            >
                                <li>
                                    <p id="facet_label_{$_expand_id}">
                                        {$filter.label}
                                    </p>

                                    <div id="slider-range_{$_expand_id}"></div>
                                </li>
                            </ul>
                        {/foreach}
                    {/block}
                {/if}
            </section>
        {/foreach}
        <div class="hidden-lg-up">
            <p class="resultProductsCount btn-black lien-up" onclick="resultProductsCount();">Afficher {$totalProductsCount} Résultats</p>
        </div>
    </div>
{*{else}*}
{*    <div id="search_filters" style="display:none;">*}
{*    </div>*}
{/if}
