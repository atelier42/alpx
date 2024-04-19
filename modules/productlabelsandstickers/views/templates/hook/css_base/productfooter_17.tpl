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
{foreach $stickers as $stick}

{if $stick.x_align != 'center' && $stick.y_align != 'center'}
    {if $stick.x_align == 'left' && $stick.y_align == 'top'}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker fmm_sticker_base_span" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="text-align:{$stick.x_align};{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9;position: relative; left: 6px; top: 6px;{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}"><span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
    {elseif $stick.x_align == 'right' && $stick.y_align == 'top'}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker fmm_sticker_base_span" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="text-align:{$stick.x_align};{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9; position: relative; right: 6px; top: 6px;{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}"><span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if} {if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
    {elseif $stick.x_align == 'left' && $stick.y_align == 'bottom'}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="text-align:{$stick.x_align};{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9; position: relative; left: 6px; bottom: 6px;{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}"><span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if} {if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
    {elseif $stick.x_align == 'right' && $stick.y_align == 'bottom'}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="text-align:{$stick.x_align};{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9; position: relative; right: 6px; bottom: 6px;{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}"><span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};;color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if} {if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
    {else}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9; position: relative; right: 6px; bottom: 6px;{if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}"><span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if} {if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}<i>{$stick.title|escape:'htmlall':'UTF-8'}</i>{/if}</span>
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
    {/if}
{elseif $stick.x_align == 'center' || $stick.y_align == 'center'}
    <span {if !empty($stick.title) && $stick.text_status > 0}class="fmm_title_text_sticker" {else}class="fmm_sticker_base_span" {/if}{if !empty($stick.url)} onclick="window.location='{$stick.url}'"{/if}style="{if !empty($stick.title) && $stick.text_status > 0}text-align:{$stick.x_align};{/if}{if empty($stick.sticker_image) && $stick.text_status > 0}width:auto;{/if}display: inline-block; z-index: 9;position: relative;{if $stick.x_align == 'center' && $stick.y_align == 'center'}left:0%; width: 100%; text-align: center;top: {$stick.axis}%;{elseif $stick.x_align == 'center' && $stick.y_align == 'top'}left:0%; width: 100%; text-align: center;top: 1%;{elseif $stick.x_align == 'center' && $stick.y_align == 'bottom'}left:0%; width: 100%; text-align: center;bottom: 1%;{elseif $stick.x_align == 'left' && $stick.y_align == 'center'}left:0%; top: {$stick.axis}%; text-align: left;{elseif $stick.x_align == 'right' && $stick.y_align == 'center'}right:0%; text-align: right; top: {$stick.axis}%;{/if} {if !empty($stick.title) && $stick.text_status > 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%;{/if}">{if !empty($stick.title) && $stick.text_status > 0}<span style="{if !empty($stick.title) && $stick.text_status > 0}background-color:{$stick.bg_color|escape:'htmlall':'UTF-8'}{/if};color:{$stick.color|escape:'htmlall':'UTF-8'};font-family:{$stick.font|escape:'htmlall':'UTF-8'};font-size:{$stick.font_size|escape:'htmlall':'UTF-8'}px;">{/if}{if !empty($stick.sticker_image)}<img style="opacity: {$stick.sticker_opacity};position: static;box-shadow:unset;{if $stick.text_status <= 0}width:{$stick.sticker_size|escape:'htmlall':'UTF-8'}%{/if}" src="{$base_image|escape:'htmlall':'UTF-8'}{$stick.sticker_image|escape:'htmlall':'UTF-8'}" />{/if}{if !empty($stick.sticker_image)}{/if}{if !empty($stick.title) && $stick.text_status > 0}{$stick.title|escape:'htmlall':'UTF-8'}{/if}{if !empty($stick.title) && $stick.text_status > 0}</span>{/if}
    {if $stick.tip > 0}<span class="fmm_hinter {if $stick.tip_pos > 0}fmm_hinter_l{if $stick.text_status > 0}_txt{/if}{else}fmm_hinter_r{if $stick.text_status > 0}_txt{/if}{/if}" style="color: {$stick.tip_color|escape:'htmlall':'UTF-8'}; background: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"><b style="color: {$stick.tip_bg|escape:'htmlall':'UTF-8'};"></b>{$stick.tip_txt|escape:'htmlall':'UTF-8'}</span>{/if}
    </span>
{/if}
{/foreach}
<style type="text/css">

{literal}

.fmm_title_text_sticker span { -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; padding: 5px;
width: auto !important; display: inline-block; text-align: center}
.fmm_title_text_sticker img { border:none!important;display: inline-block; vertical-align: middle; background: transparent !important;}
.fmm_title_text_sticker i { display: inline-block; font-style: normal}
span img { background: transparent !important; max-width: 100%;}
.product-thumbnail {position: relative;}{/literal}
</style>

