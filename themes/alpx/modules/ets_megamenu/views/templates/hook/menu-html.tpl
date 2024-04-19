{*

* 2007-2022 ETS-Soft

*

* NOTICE OF LICENSE

*

* This file is not open source! Each license that you purchased is only available for 1 wesite only.

* If you want to use this file on more websites (or projects), you need to purchase additional licenses.

* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.

*

* DISCLAIMER

*

* Do not edit or add to this file if you wish to upgrade PrestaShop to newer

* versions in the future. If you wish to customize PrestaShop for your

* needs, please contact us for extra customization service at an affordable price

*

*  @author ETS-Soft <etssoft.jsc@gmail.com>

*  @copyright  2007-2022 ETS-Soft

*  @license    Valid for 1 website (or project) for each purchase of license

*  International Registered Trademark & Property of ETS-Soft

*}



{if isset($menus) && $menus}

    <ul class="mm_menus_ul {if isset($mm_config.ETS_MM_CLICK_TEXT_SHOW_SUB) && $mm_config.ETS_MM_CLICK_TEXT_SHOW_SUB} clicktext_show_submenu{/if} {if isset($mm_config.ETS_MM_SHOW_ICON_VERTICAL)&& !$mm_config.ETS_MM_SHOW_ICON_VERTICAL} hide_icon_vertical{/if}">

        <li class="close_menu">

            <div class="pull-left">

{*                <span class="mm_menus_back">*}

{*                    <i class="icon-bar"></i>*}

{*                    <i class="icon-bar"></i>*}

{*                    <i class="icon-bar"></i>*}

{*                </span>*}
                <div class="gobackto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.888635 6.08753C0.370456 6.58961 0.370456 7.41039 0.888635 7.91247L6.27325 13.1299C6.78258 13.6234 7.60203 13.6234 8.11137 13.1299C8.62954 12.6278 8.62954 11.807 8.11137 11.3049L3.66847 7L8.11137 2.69508C8.62955 2.193 8.62955 1.37222 8.11137 0.870137C7.60204 0.376623 6.78258 0.376623 6.27325 0.870137L0.888635 6.08753Z" fill="#394061"/>
                    </svg>
                </div>
                <span class="spn-title">{l s='Menu' mod='ets_megamenu'}</span>

            </div>

            <div class="pull-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                    <path d="M9.21716 7.41597L9.5 7.6988L9.78284 7.41597L16.4154 0.783722L16.4155 0.783859L16.4241 0.774766C16.5337 0.658087 16.6657 0.564748 16.8123 0.500345L16.6514 0.134134L16.8123 0.500344C16.9582 0.436245 17.1155 0.402125 17.2748 0.400002C17.626 0.400608 17.9626 0.540369 18.211 0.788719C18.4599 1.03761 18.5997 1.37517 18.5997 1.72715H18.5996L18.5998 1.73475C18.6028 1.89201 18.5736 2.04823 18.514 2.19381C18.4545 2.33938 18.3658 2.47126 18.2534 2.58133L18.2524 2.58239L11.5335 9.21465L11.2469 9.49748L11.5316 9.78217L18.2505 16.5008L18.2505 16.5008L18.2536 16.5039C18.4633 16.709 18.587 16.9861 18.5997 17.279C18.5977 17.6282 18.4581 17.9628 18.211 18.2099C17.9621 18.4588 17.6245 18.5986 17.2725 18.5986H17.2642L17.2559 18.599C17.0912 18.6058 16.9269 18.5783 16.7733 18.5182C16.62 18.4583 16.4809 18.3671 16.3646 18.2506C16.3645 18.2504 16.3643 18.2502 16.3641 18.25L9.78467 11.5845L9.50185 11.298L9.21716 11.5827L2.56736 18.2322L2.56732 18.2321L2.56282 18.2368C2.45362 18.3496 2.32316 18.4396 2.17897 18.5017C2.03551 18.5635 1.88129 18.5965 1.72512 18.5986C1.37397 18.598 1.03735 18.4582 0.789009 18.2099C0.540109 17.961 0.400281 17.6235 0.400281 17.2715H0.400353L0.400209 17.2639C0.397218 17.1066 0.426396 16.9504 0.485954 16.8048C0.545511 16.6593 0.634191 16.5274 0.746554 16.4173L0.747632 16.4162L7.46652 9.78399L7.75306 9.50115L7.46836 9.21647L0.749468 2.49786L0.749485 2.49784L0.74635 2.49478C0.536682 2.28966 0.413021 2.01251 0.400302 1.71968C0.402266 1.3704 0.541871 1.03585 0.789009 0.788719C1.03732 0.540422 1.37389 0.400667 1.72498 0.400002C2.03414 0.404514 2.33074 0.52984 2.55009 0.749179L9.21716 7.41597Z" fill="white" stroke="#DD2113" stroke-width="0.8"/>
                </svg>
                {*
                    <span class="mm_menus_back_icon"></span>
                    {l s='Back' mod='ets_megamenu'}
                *}
            </div>

        </li>

        {foreach from=$menus item='menu'}
            <li class="mm_menus_li{if $menu.enabled_vertical} mm_menus_li_tab{if $menu.menu_ver_hidden_border} mm_no_border{/if}{if $menu.menu_ver_alway_show} menu_ver_alway_show_sub{/if}{/if}{if $menu.custom_class} {$menu.custom_class|escape:'html':'UTF-8'}{/if}{if $menu.sub_menu_type} mm_sub_align_{strtolower($menu.sub_menu_type)|escape:'html':'UTF-8'}{/if}{if $menu.columns} mm_has_sub{/if}{if $menu.display_tabs_in_full_width && $menu.enabled_vertical} display_tabs_in_full_width{/if}{if isset($mm_config.ETS_MM_DISPLAY_SUBMENU_BY_CLICK) && $mm_config.ETS_MM_DISPLAY_SUBMENU_BY_CLICK } click_open_submenu{else} hover {/if}"

                {if $menu.enabled_vertical}style="width: {if $menu.menu_item_width}{$menu.menu_item_width|escape:'html':'UTF-8'}{else}{*230px*}auto;{/if}"{/if}>

                <a class="ets_mm_url" {if isset($menu.menu_open_new_tab) && $menu.menu_open_new_tab == 1} target="_blank"{/if}

                   href="{$menu.menu_link|escape:'html':'UTF-8'}"

                   style="{if $menu.enabled_vertical}{if isset($menu.menu_ver_text_color) && $menu.menu_ver_text_color}color:{$menu.menu_ver_text_color|escape:'html':'UTF-8'};{/if}{if isset($menu.menu_ver_background_color) && $menu.menu_ver_background_color}background-color:{$menu.menu_ver_background_color|escape:'html':'UTF-8'};{/if}{/if}{if Configuration::get('ETS_MM_HEADING_FONT_SIZE')}font-size:{Configuration::get('ETS_MM_HEADING_FONT_SIZE')|intval}px;{/if}">

                    <span class="mm_menu_content_title">

                        {if $menu.menu_img_link}

                            <img src="{$menu.menu_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20"/>

                                {elseif $menu.menu_icon}

                            <i class="fa {$menu.menu_icon|escape:'html':'UTF-8'}"></i>

                        {/if}

                        {$menu.title|escape:'html':'UTF-8'}

                        {if $menu.columns}<span class="mm_arrow"></span>{/if}

                        {if $menu.bubble_text}<span class="mm_bubble_text"style="background: {if $menu.bubble_background_color}{$menu.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $menu.bubble_text_color|escape:'html':'UTF-8'}{$menu.bubble_text_color|escape:'html':'UTF-8'}{else}#ffffff{/if};">{$menu.bubble_text|escape:'html':'UTF-8'}</span>{/if}

                    </span>

                </a>

                {if $menu.enabled_vertical}

                    {if $menu.tabs}

                        <span class="arrow closed"></span>

                    {/if}

                {/if}

                {if $menu.enabled_vertical}

                    {if $menu.tabs}

                        <ul class="mm_columns_ul mm_columns_ul_tab {if $menu.menu_ver_alway_show} mm_columns_ul_tab_content{/if}"

                            style="width:{$menu.sub_menu_max_width|escape:'html':'UTF-8'};{if Configuration::get('ETS_MM_TEXT_FONT_SIZE')} font-size:{Configuration::get('ETS_MM_TEXT_FONT_SIZE')|intval}px;{/if}">

                            {foreach from=$menu.tabs key='key' item='tab'}

                                <li class="mm_tabs_li{if $tab.columns} {if $key == 0 && isset($menu.menu_ver_alway_open_first) && $menu.menu_ver_alway_open_first}open menu_ver_alway_open_first {/if}mm_tabs_has_content{/if}{if !$tab.tab_sub_content_pos} mm_tab_content_hoz{/if} {if isset($menu.menu_ver_alway_open_first) && $menu.menu_ver_alway_open_first && $menu.menu_ver_alway_show}open_first{/if} {if !$menu.menu_ver_alway_show} ver_alway_hide{/if}">

                                    <div class="mm_tab_li_content closed"

                                         style="width: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if}">

                                        <span class="mm_tab_name mm_tab_toggle{if $tab.columns} mm_tab_has_child{/if}">

                                            <span class="mm_tab_toggle_title">

                                                {if $tab.url}

                                                    <a class="ets_mm_url" href="{$tab.url|escape:'html':'UTF-8'}">

                                                {/if}

                                                        {if $tab.tab_img_link}

                                                            <img src="{$tab.tab_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20"/>

                                                    {else if $tab.tab_icon}

                                                        <i class="fa {$tab.tab_icon|escape:'html':'UTF-8'}"></i>

                                                        {/if}

                                                        {$tab.title|escape:'html':'UTF-8'}

                                                        {if $tab.bubble_text}<span class="mm_bubble_text" style="background: {if $tab.bubble_background_color}{$tab.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $tab.bubble_text_color|escape:'html':'UTF-8'}{$tab.bubble_text_color|escape:'html':'UTF-8'}{else}#ffffff{/if};">{$tab.bubble_text|escape:'html':'UTF-8'}</span>{/if}

                                                        {if $tab.url}

                                                    </a>

                                                {/if}

                                            </span>

                                        </span>

                                    </div>

                                    {if $tab.columns}

                                        <ul class="mm_columns_contents_ul "

                                            style="{if $tab.tab_sub_width}width: {$tab.tab_sub_width|escape:'html':'UTF-8'};{else}{if $menu.tab_item_width} width:calc(100% - {$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if} + 2px);{/if} left: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};right: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};{if $tab.background_image} background-image:url('{$tab.background_image|escape:'html':'UTF-8'}');background-position:{$tab.position_background|escape:'html':'UTF-8'}{/if}">

                                            {foreach from=$tab.columns item='column'}

                                                <li class="mm_columns_li column_size_{$column.column_size|intval} {if $column.is_breaker}mm_breaker{/if} {if $column.blocks}mm_has_sub{/if}">

                                                    {if isset($column.blocks) && $column.blocks}

                                                        <ul class="mm_blocks_ul">

                                                            {foreach from=$column.blocks item='block'}

                                                                <li data-id-block="{$block.id_block|intval}"

                                                                    class="mm_blocks_li">

                                                                    {hook h='displayBlock' block=$block}

                                                                </li>

                                                            {/foreach}

                                                        </ul>

                                                    {/if}

                                                </li>

                                            {/foreach}

                                        </ul>

                                    {/if}

                                </li>

                            {/foreach}

                        </ul>

                    {/if}

                {else}

                    {if $menu.columns}<span class="arrow closed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.72675 0.870137L8.11136 6.08753C8.62954 6.58961 8.62954 7.41039 8.11137 7.91247L2.72675 13.1299C2.21742 13.6234 1.39797 13.6234 0.888634 13.1299C0.370456 12.6278 0.370456 11.807 0.888634 11.3049L5.33153 7L0.888633 2.69508C0.370454 2.193 0.370454 1.37222 0.888632 0.870137C1.39796 0.376623 2.21742 0.376623 2.72675 0.870137Z" fill="#394061"/>
                            </svg>
                    </span>{/if}

                    {if $menu.columns}

                        <div class="mm_columns_ul"

                             style=" width:{$menu.sub_menu_max_width|escape:'html':'UTF-8'};{if Configuration::get('ETS_MM_TEXT_FONT_SIZE')} font-size:{Configuration::get('ETS_MM_TEXT_FONT_SIZE')|intval}px;{/if}{if !$menu.enabled_vertical && $menu.background_image} background-image:url('{$menu.background_image|escape:'html':'UTF-8'}');background-position:{$menu.position_background|escape:'html':'UTF-8'}{/if}">
                            <ul class="content-ul-menu">

                                {foreach from=$menu.columns item='column'}

                                    <li class="mm_columns_li column_size_{$column.column_size|intval} {if $column.is_breaker}mm_breaker{/if} {if $column.blocks}mm_has_sub{/if}">

                                        {if isset($column.blocks) && $column.blocks}

                                            <ul class="mm_blocks_ul">

                                                {foreach from=$column.blocks item='block'}

                                                    <li data-id-block="{$block.id_block|intval}" class="mm_blocks_li">

                                                        {hook h='displayBlock' block=$block}

                                                    </li>

                                                {/foreach}

                                            </ul>

                                        {/if}

                                    </li>

                                {/foreach}
                            </ul>
                        </div>

                    {/if}

                {/if}

            </li>
        {/foreach}
        <div class="avis-linkmobile">{hook h='displayCustomLink'}</div>
        <li class="mm_menus_li mm_sub_align_full bottom_menu hidden-md-up fine">
            {hook h='displayNav2'}
        </li>
    </ul>
    {hook h='displayCustomMenu'}
{/if}

<script type="text/javascript">

    var Days_text = '{l s='Day(s)' mod='ets_megamenu' js=1}';

    var Hours_text = '{l s='Hr(s)' mod='ets_megamenu' js=1}';

    var Mins_text = '{l s='Min(s)' mod='ets_megamenu' js=1}';

    var Sec_text = '{l s='Sec(s)' mod='ets_megamenu' js=1}';

</script>