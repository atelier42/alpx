{*
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @category  FMM Modules
* @package   productlabelsandstickers
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{if !empty($stickers)}<script type="text/javascript" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}js/jquery/jquery-1.11.0.min.js"></script>{/if}
{foreach $stickers as $stick}
{literal}
<script>
{/literal}
{if $stick.x_align != 'center' && $stick.y_align != 'center'}
    {if {$stick.x_align} == 'left' && {$stick.y_align} == 'top'}{literal}
    $('div.product-cover')
    .prepend("<span {/literal}{if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}{literal}style='{/literal}text-align:{$stick.x_align}{literal};{/literal}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}{literal}display: inline-block; position: absolute; left: 6px; top: 6px;{/literal}{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}{literal}'><span style='{/literal}{if !empty($stick.title) && $stick.text_status > 0}{literal}background-color:{/literal}{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if}{literal};color:{/literal}{$stick.color|escape:'htmlall':'UTF-8'}{literal};font-family:{/literal}{$stick.font|escape:'htmlall':'UTF-8'}{literal};font-size:{/literal}{$stick.font_size|escape:'htmlall':'UTF-8'}px{literal};'>{/literal}{if !empty($stick.sticker_image)}<img style=\"box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}\" src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{literal} {/literal}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>{if $stick.tip > 0}<span class='fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}' style='color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'><b style='color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}{literal}</span>");
    {/literal}{elseif {$stick.x_align} == 'right' && {$stick.y_align} == 'top'}{literal}
    $('div.product-cover')
    .prepend("<span {/literal}{if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}{literal}style='{/literal}text-align:{$stick.x_align}{literal};{/literal}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}{literal}display: inline-block; position: absolute; right: 6px; top: 6px;{/literal}{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}{literal}'><span style='{/literal}{if !empty($stick.title) && $stick.text_status > 0}{literal}background-color:{/literal}{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if}{literal};color:{/literal}{$stick.color|escape:'htmlall':'UTF-8'}{literal};font-family:{/literal}{$stick.font|escape:'htmlall':'UTF-8'}{literal};font-size:{/literal}{$stick.font_size|escape:'htmlall':'UTF-8'}px{literal};'>{/literal}{if !empty($stick.sticker_image)}<img style=\"box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}\" src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{literal} {/literal}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>{if $stick.tip > 0}<span class='fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}' style='color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'><b style='color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}{literal}</span>");
    {/literal}{elseif {$stick.x_align} == 'left' && {$stick.y_align} == 'bottom'}{literal}
    $('div.product-cover')
    .append("<span {/literal}{if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}{literal}style='{/literal}text-align:{$stick.x_align}{literal};{/literal}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}{literal}display: inline-block; position: absolute; left: 6px; bottom: 6px;{/literal}{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}{literal}'><span style='{/literal}{if !empty($stick.title) && $stick.text_status > 0}{literal}background-color:{/literal}{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if}{literal};color:{/literal}{$stick.color|escape:'htmlall':'UTF-8'}{literal};font-family:{/literal}{$stick.font|escape:'htmlall':'UTF-8'}{literal};font-size:{/literal}{$stick.font_size|escape:'htmlall':'UTF-8'}px{literal};'>{/literal}{if !empty($stick.sticker_image)}<img style=\"box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}\" src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{literal} {/literal}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>{if $stick.tip > 0}<span class='fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}' style='color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'><b style='color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}{literal}</span>");
    {/literal}{elseif $stick.x_align == 'right' && $stick.y_align == 'bottom'}{literal}
    $('div.product-cover')
    .append("<span {/literal}{if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}{literal}style='{/literal}text-align:{$stick.x_align}{literal};{/literal}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}{literal}display: inline-block; position: absolute; right: 6px; bottom: 6px;{/literal}{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}{literal}'><span style='{/literal}{if !empty($stick.title) && $stick.text_status > 0}{literal}background-color:{/literal}{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if}{literal};;color:{/literal}{$stick.color|escape:'htmlall':'UTF-8'}{literal};font-family:{/literal}{$stick.font|escape:'htmlall':'UTF-8'}{literal};font-size:{/literal}{$stick.font_size|escape:'htmlall':'UTF-8'}px{literal};'>{/literal}{if !empty($stick.sticker_image)}<img style=\"box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}\" src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{literal} {/literal}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>{if $stick.tip > 0}<span class='fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}' style='color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'><b style='color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}{literal}</span>");
    {/literal}{else}{literal}
    $('div.product-cover')
    .append("<span {/literal}{if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}{literal}style='{/literal}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}{literal}display: inline-block; position: absolute; right: 6px; bottom: 6px;{/literal}{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}{literal}'><span style='{/literal}{if !empty($stick.title) && $stick.text_status > 0}{literal}background-color:{/literal}{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if}{literal};color:{/literal}{$stick.color|escape:'htmlall':'UTF-8'}{literal};font-family:{/literal}{$stick.font|escape:'htmlall':'UTF-8'}{literal};font-size:{/literal}{$stick.font_size|escape:'htmlall':'UTF-8'}px{literal};'>{/literal}{if !empty($stick.sticker_image)}<img style=\"box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}\" src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{literal} {/literal}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>{if $stick.tip > 0}<span class='fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}' style='color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'><b style='color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};'></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}{literal}</span>");
    {/literal}{/if}
{elseif $stick.x_align == 'center' || $stick.y_align == 'center'}{literal}
$('div.product-cover')
    .prepend("{/literal}<span {if !empty($stick.title) && $stick.text_status > 0}class='fmm_title_text_sticker fmm_sticker_base_span'{else}class='fmm_sticker_base_span' {/if}style='{if !empty($stick.title) && $stick.text_status > 0}text-align:{$stick.x_align};{/if}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9;position: absolute;{if $stick.x_align == 'center' && $stick.y_align == 'center'}left:0%; width: 100%; text-align: center;top: {$stick.axis}%;{elseif $stick.x_align == 'center' && $stick.y_align == 'top'}left:0%; width: 100%; text-align: center;top: 1%;{elseif $stick.x_align == 'center' && $stick.y_align == 'bottom'}left:0%; width: 100%; text-align: center;bottom: 1%;{elseif $stick.x_align == 'left' && $stick.y_align == 'center'}left:0%; top: {$stick.axis}%; text-align: left;{elseif $stick.x_align == 'right' && $stick.y_align == 'center'}right:0%; text-align: right; top: {$stick.axis}%;{/if} {if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}'>{if !empty($stick.title) && $stick.text_status > 0}<span style='{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;'>{/if}{if !empty($stick.sticker_image)}<img style='box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}' src='{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}' />{/if}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}{if !empty($stick.title) && $stick.text_status > 0}</span>{/if}</span>");
{/if}
{literal}
</script>
<style type="text/css">
.fmm_title_text_sticker span { -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; padding: 5px;
width: auto !important; display: inline-block; text-align: center}
.fmm_title_text_sticker img { border:none!important;display: inline-block; vertical-align: middle; background: transparent !important;}
.fmm_title_text_sticker i { display: inline-block; font-style: normal}
span img { background: transparent !important; max-width: 100%;}
</style>
{/literal}
{/foreach}
{if !empty($stickers_banner.title)}
{if empty($stickers)}<script type="text/javascript" src="{if $force_ssl == 1}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}js/jquery/jquery-1.11.0.min.js"></script>{/if}
{literal}
<script>
$('.product-price').after({/literal}'<div style="padding:10px 6px; margin-bottom:10px; text-align:center;background:{$stickers_banner.bg_color|escape:'htmlall':'UTF-8'};color:{$stickers_banner.color|escape:'htmlall':'UTF-8'};border:1px solid {$stickers_banner.border_color|escape:'htmlall':'UTF-8'};font-family:{$stickers_banner.font|escape:'htmlall':'UTF-8'};font-size:{$stickers_banner.font_size|escape:'htmlall':'UTF-8'}px;font-weight:{$stickers_banner.font_weight|escape:'htmlall':'UTF-8'};">{$stickers_banner.title|escape:'htmlall':'UTF-8'}</div>'{literal});
</script>
{/literal}
{/if}


