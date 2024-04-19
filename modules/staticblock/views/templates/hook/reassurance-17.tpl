{*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2020 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{include file='./style.tpl'}
{if !empty($reassurance)}
    <h1 style="text-align:center">{l s='Reassurance Block' mod='staticblock'}<h1>
    <div class="row text-center">
        <div class="col-md-12 cards empty_class">
            {foreach $reassurance as $assurance}
                {if ($assurance.apperance == 'home' && $home == 2) || ($assurance.apperance == 'footer' && $footer == 2) || ($assurance.apperance == 'product-footer' && $product == 2)}
                    {include file='./collapse-style.tpl'}
                    <div class="card fmm-is-closed col-md-3">
                        <div class="fmm_inner_card fmm_js_opener fmm_ch" data-hover-color="hover-color">
                            <img class="table-cell" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}img/sbr/{$assurance.image|escape:'htmlall':'UTF-8'}" alt="icon">
                            <span class="fmm-title">{$assurance.title|escape:'htmlall':'UTF-8'}</span>
                        </div>
                        <div class="fmm_opener_card">
                            <span class="fmm_sub_title">{$assurance.sub_title|escape:'htmlall':'UTF-8'}</span>
                            <p class="fmm_cl_desc">{$assurance.description nofilter}{* Html Content *}</p>
                            <div class="text-right">
                                <a style="color:{$c_link|escape:'htmlall':'UTF-8'}" href="{$assurance.link|escape:'htmlall':'UTF-8'}" target="_blank">{l s='Click here for more information' mod='staticblock'}</a>
                            </div>
                        </div>
                    </div>
                {elseif ($assurance.apperance == 'home' && $home == 1) || ($assurance.apperance == 'footer' && $footer == 1) || ($assurance.apperance == 'product-footer' && $product == 1)}
                    {include file='./hover-style.tpl'}
                    <div class="col-md-3 text-center fmm_dimensions fmm_style_box">
                        <div class="fmm_padding_0 fmm-hovers" data-hover-color="{$t_hover|escape:'htmlall':'UTF-8'}">
                            <a href="{$assurance.link|escape:'htmlall':'UTF-8'}" target="_blank" class="fmm_link_style">
                                <div class="fmm_table-grid bottom">
                                    <img class="table-cell fmm_image_align fmm_font_size_15" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}img/sbr/{$assurance.image|escape:'htmlall':'UTF-8'}" alt="icon">
                                    <span class="fmm_span_align">
                                        <span class="subtitle_style fmm_black_color">{$assurance.title|escape:'htmlall':'UTF-8'}</span>
                                        <p class="fmm_margin_10 fmm_font_size_13">{$assurance.sub_title|escape:'htmlall':'UTF-8'}</p>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                {elseif ($assurance.apperance == 'home' && $home == 3) || ($assurance.apperance == 'footer' && $footer == 3) || ($assurance.apperance == 'product-footer' && $product == 3)}
                    {include file='./hover2-style.tpl'}
                    <div class="col-md-3 text-center fmm_dimension" style="background:{$h2_back|escape:'htmlall':'UTF-8'}; margin-left:10px;">
                        <div class="fmm_padding_0 fmm_style2_box">
                            <a href="{$assurance.link|escape:'htmlall':'UTF-8'}" class="fmm_link_style">
                                <div class="fmm_table_grid2 bottom">
                                    <div class="fmm_icon">
                                        <img class="fmm_image_size" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}img/sbr/{$assurance.image|escape:'htmlall':'UTF-8'}" alt="icon">
                                    </div>
                                    <h3 class="fmm_title_style2">{$assurance.title|escape:'htmlall':'UTF-8'}</h3>
                                    <h5 class="fmm_margin_10 fmm_font_size_13 fmm_subtitle_style2">{$assurance.description nofilter} {* Html Content *}</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                {elseif ($assurance.apperance == 'home' && $home == 4) || ($assurance.apperance == 'footer' && $footer == 4) || ($assurance.apperance == 'product-footer' && $product == 4)}
                    {include file='./tooltip-style.tpl'}
                    <div class="text-center fmm_dimension">
                        <div class="col-md-3" style="background-color:{$t_back|escape:'htmlall':'UTF-8'}">
                            <a href="{$assurance.link|escape:'htmlall':'UTF-8'}" class="fmm_link_style">
                                <div class="fmm_table-grid con-tooltip bottom">
                                    <img class="table-cell fmm_image_align" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}img/sbr/{$assurance.image|escape:'htmlall':'UTF-8'}" alt="icon">
                                    <span class="fmm_span_align fmm-color">
                                        {$assurance.title|escape:'htmlall':'UTF-8'}
                                    </span>
                                    <div class="tooltip">
                                        <span class="fmm_sub_titles">{$assurance.sub_title|escape:'htmlall':'UTF-8'}</span>
                                        <p class="fmm_margin_10 ">{$assurance.description nofilter}{* Html Content *}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                {/if}
            {/foreach}         
        </div>
    </div>
{/if}