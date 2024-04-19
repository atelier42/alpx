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
{literal}
<style type="text/css">
.dragcontainer{border:1px solid #000;position: relative; overflow:hidden;}
.dragobject{border: 1px solid #000;position:absolute;}
</style>
{/literal}
<div class="panel-heading"><i class="icon-cogs"></i> {l s='Stickers Banners' mod='productlabelsandstickers'}</div>
		<div class="form-group margin-form">
			<label class="control-label col-lg-3">{l s='Banner Text' mod='productlabelsandstickers'}</label>
			<div class="margin-form col-lg-7">
				{assign var=divLangName value='cpara&curren;dd'}
				{foreach from=$languages item=language}
					<div class="lang_{$language.id_lang|escape:'htmlall':'UTF-8'} col-lg-9" id="cpara_{$language.id_lang|escape:'htmlall':'UTF-8'}" style="display: {if $language.id_lang == $current_lang} block{else}none{/if};float: left;">
						<input type="text" id="sticker_text{$language.id_lang|escape:'htmlall':'UTF-8'}" name="sticker_text{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{$current_object->getFieldTitle($stickersbanners_id, $language.id_lang)|escape:'htmlall':'UTF-8'}" />
					</div>
				{/foreach}
				<div class="col-lg-2">{$module->displayFlags($languages, $current_lang, $divLangName, 'cpara', true)}{* html code *}</div>
			</div>
		</div>
		<div class="form-group margin-form">
			<label class="col-lg-3 control-label">{l s='Color' mod='productlabelsandstickers'}</label>
			<div class="col-lg-5">
				<div class="input-group">
				<input type="text" class="mColorPicker mColorPickerTrigger form-control" style="display:inline-block;color:#fff;background-color:{$color|escape:'htmlall':'UTF-8'}" id="color_0" value="{$color|escape:'htmlall':'UTF-8'}" name="color" data-hex="true" /><span id="icp_color_0" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" /></span>
				</div>
			</div><div class="clearfix"></div>
		</div>
		
		<div class="form-group margin-form">
			<label class="col-lg-3 control-label">{l s='Background Color' mod='productlabelsandstickers'}</label>
			<div class="col-lg-5">
				<div class="input-group">
				<input type="text" class="mColorPicker mColorPickerTrigger form-control" style="display:inline-block;color:#fff;background-color:{$bg_color|escape:'htmlall':'UTF-8'}" id="color_1" value="{$bg_color|escape:'htmlall':'UTF-8'}" name="bg_color" data-hex="true" /><span id="icp_color_1" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" /></span>
				</div>
			</div><div class="clearfix"></div>
		</div>
		
		<div class="form-group margin-form">
			<label class="col-lg-3 control-label">{l s='Border Color' mod='productlabelsandstickers'}</label>
			<div class="col-lg-5">
				<div class="input-group">
				<input type="text" class="mColorPicker mColorPickerTrigger form-control" style="display:inline-block;color:#fff;background-color:{$border_color|escape:'htmlall':'UTF-8'}" id="color_2" value="{$border_color|escape:'htmlall':'UTF-8'}" name="border_color" data-hex="true" /><span id="icp_color_2" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" /></span>
				</div>
			</div><div class="clearfix"></div>
		</div>
		
		<div class="form-group margin-form">
				<label class="col-lg-3 control-label">{l s='Font' mod='productlabelsandstickers'}</label>
				<div class="col-lg-9">
					<select id="font" name="font" class="form-control fixed-width-xxl">
						{if !empty($font)}<option value="{$font|escape:'htmlall':'UTF-8'}" selected="selected">{$font|escape:'htmlall':'UTF-8'}</option>{/if}
						<option value="Arial">Arial</option>
						<option value="Open Sans">Open Sans</option>
						<option value="Helvetica">Helvetica</option>
						<option value="sans-serif">sans-serif</option>
					</select>
				</div>
		</div>
		
		<div class="form-group margin-form">
				<label class="col-lg-3 control-label">{l s='Font Size' mod='productlabelsandstickers'}</label>
				<div class="col-lg-9">
					<select id="font_size" name="font_size" class="form-control fixed-width-xxl">
						{if $font_size > 0}<option value="{$font_size|escape:'htmlall':'UTF-8'}" selected="selected">{$font_size|escape:'htmlall':'UTF-8'}</option>{/if}
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
					</select>
				</div>
		</div>
		
		<div class="form-group margin-form">
			<label class="col-lg-3 control-label">{l s='Font Weight' mod='productlabelsandstickers'}</label>
			<div class="col-lg-9">
				<select id="font_weight" name="font_weight" class="form-control fixed-width-xxl">
					{if !empty($font_weight)}<option value="{$font_weight|escape:'htmlall':'UTF-8'}" selected="selected">{$font_weight|escape:'htmlall':'UTF-8'}</option>{/if}
					<option value="bold">bold</option>
					<option value="normal">normal</option>
				</select>
			</div>
		</div>
		<div class="form-group">
				<label class="control-label col-lg-3">{l s='Start Date(Optional)' mod='productlabelsandstickers'}:</label>
				<div class="col-lg-3">
					<div class="input-group">
						<input type="text" class="startdatepicker" value="{$start_date|escape:'htmlall':'UTF-8'}" name="start_date">
						<span class="input-group-addon"><img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/productlabelsandstickers/views/img/date.png"></span>
					</div>
				</div><div class="clearfix"></div>
			</div>

			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Expiry Date(Optional)' mod='productlabelsandstickers'}:</label>
				<div class="col-lg-3">
					<div class="input-group">
						<input type="text" class="expirydatepicker" value="{$expiry_date|escape:'htmlall':'UTF-8'}" name="expiry_date">
						<span class="input-group-addon"><img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/productlabelsandstickers/views/img/date.png"></span>
					</div>
				</div><div class="clearfix"></div>
			</div>
		<!-- Multishop -->
		{if isset($shops) AND $shops}
			<label class="col-lg-3 control-label">{l s='Shop Association' mod='productlabelsandstickers'}</label>
			<div class="margin-form form-group">
	          <div class="col-lg-6">{$shops}{* html content *}
				</div>
			</div>
			<div class="clearfix"></div>
		{/if}

<script type="text/javascript">
var selected_shops = "{$selected_shops|escape:'htmlall':'UTF-8'}";
$(document).ready(function()
{
	$('.displayed_flag').addClass('btn btn-default');

	// shop association
    $(".tree-item-name input[type=checkbox]").each(function()
    {

        $(this).prop("checked", false);
        $(this).removeClass("tree-selected");
        $(this).parent().removeClass("tree-selected");
        if ($.inArray($(this).val(), selected_shops) != -1)
            {
                $(this).prop("checked", true);
                $(this).parent().addClass("tree-selected");
                $(this).parents("ul.tree").each(
                    function()
                    {
                        $(this).children().children().children(".icon-folder-close")
                            .removeClass("icon-folder-close")
                            .addClass("icon-folder-open");
                        $(this).show();
                    }
                );
            }

    });
	
	$('.expirydatepicker, .startdatepicker').datepicker({
		prevText 	: '',
		nextText 	: '',
		dateFormat	: 'yy-mm-dd',
		// Define a custom regional settings in order to use PrestaShop translation tools
		currentText : '{l s='Now' mod='productlabelsandstickers' js=1}',
		closeText 	: '{l s='Done' mod='productlabelsandstickers' js=1}',
		ampm 		: false,
		amNames 	: ['AM', 'A'],
		pmNames 	: ['PM', 'P'],
		timeFormat 	: 'hh:mm:ss tt',
		timeSuffix 	: '',
		timeOnlyTitle: '{l s='Choose Time' mod='productlabelsandstickers' js=1}',
		timeText 	: '{l s='Time' mod='productlabelsandstickers' js=1}',
		hourText 	: '{l s='Hour' mod='productlabelsandstickers' js=1}',
		minuteText 	: '{l s='Minute' mod='productlabelsandstickers' js=1}',
	});
})
</script>