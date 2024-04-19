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
{literal}
<link  href="../modules/relatedproducts/views/css/jquery.autocomplete.css" rel="stylesheet" type="text/css" media="all" />		
<script type="text/javascript">
var success_msg		= "{/literal}{l s='Product successfully added to list' mod='relatedproducts' js=1}{literal}";
var error_msg		= "{/literal}{l s='Product already added to the list' mod='relatedproducts' js=1}{literal}";
var delete_label	= "{/literal}{l s='Delete' mod='relatedproducts' js=1}{literal}";
var img_path		= "{/literal}{$smarty.const._PS_ADMIN_IMG_|escape:'htmlall':'UTF-8'}{literal}";
var version 		= "{/literal}{$ps_version|escape:'htmlall':'UTF-8'}{literal}";

$('document').ready(function()
{
	var link = jQuery('.spy').val();
	var lang = jQuery('.lang_spy').val();
			$(".product_autocomplete_input")
				.autocomplete(
				"{/literal}{if $search_ssl == 1}{$link->getPageLink('search', true)|addslashes|escape:'htmlall':'UTF-8'}{else}{$link->getPageLink('search')|addslashes|escape:'htmlall':'UTF-8'}{/if}{literal}",
					{
						minChars: 3,
						max: 10,
						width: 500,
						selectFirst: false,
						scroll: false,
						dataType: "json",
						formatItem: function(data, i, max, value, term) {
							return value;
						},
						parse: function(data) {
							var mytab = new Array();
							for (var i = 0; i < data.length; i++)
								mytab[mytab.length] = { data: data[i], value: data[i].id_product + ' - ' + data[i].pname };
							return mytab;
						},
						extraParams: {
							ajaxSearch: 1,
							id_lang: lang
						}
					}
				)
				.result(function(event, data, formatted)
				{
					if ( data.id_product.length > 0 && data.pname.length > 0 )
						{
							var width = data.pname.length *9;
		 					$("#product_autocomplete_input").val(data.pname);
		 					$("#product_id").val(data.id_product);
		 					$("#product_id").trigger('change');
		 					$("#product_name").val(data.pname);
		 				}
					
				})
    });

	function searchProducts()
	{
		var subURL = "{/literal}{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure=relatedproducts&tab_module=front_office_features&module_name=relatedproducts{literal}";

		$('#products_part').show();
		$.ajax({
			type	: "POST",
			cache	: false,
			url		: htmlEncode(subURL),
			async: true,
			data : {
				ajax: "1",
				action: "allcombinations",
				id_related: $('#product_id').val(),
				//product_search: $('#product_id').val()},
			},
			success : function(data)
			{
				if(data)
				{
					$('#product_attribute').html(data);
				}
									
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
	});
}

function add_to_list()
{
	var id_related = $('#product_id').val();
	var id_combination = (typeof $('#product_attribute').val() != 'undefined' && $('#product_attribute').val())? $('#product_attribute').val() :0;

	if (typeof id_related != 'undefined' && id_related)
	{
		var selected = 'row_'+ id_related +'_'+ id_combination;
		if (isExists(selected))
			showErrorMessage(error_msg);
		else
		{
			var row = $('<tr class="related_products" id="row_'+ id_related +'_'+ id_combination+'">'
				+'<td>'+id_related +'<input type="hidden" name="related_products[]['+ id_related +']" value="'+ id_combination +'"></td>'
				+'<td>'+ id_combination +'</td>'
				+'<td>'+ $('#product_name').val() +'</td>'
				+'<td><a class="button btn btn-danger" href="javascript:;" onclick="$(this).parent().parent().remove();" title="'+ delete_label +'">'+((parseFloat(version) >= 1.6)? '<i class="icon-trash"></i> ' : '<img src="'+ img_path +'delete.gif" alt="'+ delete_label +'" />')+'&nbsp;'+ delete_label +'</a></td></tr>');

			$('#related-list').append(row);
			showSuccessMessage(success_msg);
		}
	}
}

$(document).on('click', '.delete_related_product', function(event){
	event.preventDefault();
	var ajaxUrl = $(this).attr('href');
	var requestData = {
		id_current: parseInt($(this).data('current')),
		product_id: parseInt($(this).data('pid')),
		id_comb: parseInt($(this).data('ipa')),
		action: 'deleteRelatedproduct'
	}

	if (requestData.product_id) {
		$.post(ajaxUrl, requestData, null, 'json').then(function (response) {
			if (response.success) {
				$('#related-list').find('#row_' + requestData.product_id + '_' + requestData.id_comb).remove();
				showSuccessMessage(response.msg);
			} else if (response.success == false) {
				showErrorMessage(response.msg);
			}
		}).fail(function (response) {
			showErrorMessage(response.msg);
		});
	}
})

function isExists(row)
{
	var result = false;
	$('.related_products').each(function()
	{
		if($(this).attr('id') == row.toString()) {
			result = true;
		}
	});
	return result;
}

function htmlEncode(input) {
    return String(input).replace(/&amp;/g, '&');
}
</script>
{/literal} 

<div class="panel">
	<h3 class="panel-heading">{l s='Related Products' mod='relatedproducts'}</h3> 
	<div class="form-group">   
		<label class="col-lg-3 control-label">{l s='Search a product' mod='relatedproducts'}</label>
		<div class="col-lg-6">
			<div class="input-group">
			    <input name="product_selected" type="text" id="product_name" class="product_autocomplete_input ac_input" value="" autocomplete="off"/>
			    <input id="product_id" type="hidden" name="product_id" value="" onchange="searchProducts();"/>
			    <input class="spy" type="hidden" value="{$link->getPageLink('search')|escape:'htmlall':'UTF-8'}" />
			    <input class="lang_spy" type="hidden" value="{$id_lang|escape:'htmlall':'UTF-8'}" />
			    <span class="input-group-addon"><i class="icon-search"></i></span>
			</div>
	    </div>
	</div>
	<div class="{if $ps_version >= 1.6}clearfix{else}margin-form{/if}"></div>
	<div id="products_part" style="display:none">
		<div class="form-group">
			<label class="col-lg-3 control-label">{l s='Combinations' mod='relatedproducts'}</label>
			<div class="col-lg-5">
				<select id="product_attribute" name="product_attribute"></select>
				<p class="margin-form hint-block help-block">{l s='Dont forget to save your products list.' mod='relatedproducts'}</p>
			</div>
			<div class="col-lg-4 margin-form">
				<a class="button btn btn-default" name="javascript:;" onclick="add_to_list()"><span><i class="icon-plus"></i> {l s='Add this Product' mod='relatedproducts'}</span>
				</a>
			</div>
		</div>
		<div class="{if $ps_version >= 1.6}clearfix{else}margin-form{/if}"></div>
	</div>

	<h4 class="panel-heading" style="margin-top: 16px">{l s='Related Products List' mod='relatedproducts'}</h4>
	<table class="table std" {if $ps_version < 1.6}width="100%"{/if}>
		<thead>
	     <tr>
	     	<th>{l s='Product ID' mod='relatedproducts'}</th>
	     	<th>{l s='Combination ID' mod='relatedproducts'}</th>
	     	<th>{l s='Product Name' mod='relatedproducts'}</th>
	     	<th>{l s='Actions' mod='relatedproducts'}</th>
		 </tr>
		</thead>
		<tbody id="related-list">
		{if count($products_list) > 0}
			{foreach from=$products_list item=rec}
				<tr class="related_products" id="row_{$rec.id_related|escape:'htmlall':'UTF-8'}_{$rec.id_combination|escape:'htmlall':'UTF-8'}">
					<td>{$rec.id_related|escape:'htmlall':'UTF-8'}</td>
					<td>{$rec.id_combination|escape:'htmlall':'UTF-8'}</td>
					<td>{Product::getProductName($rec.id_related, $rec.id_combination)|escape:'htmlall':'UTF-8'}</td>
					<td>
						<a class="button btn btn-danger delete_related_product" href="{$link->getAdminLink('AdminModules')|escape:'htmlall':'UTF-8'}&configure=relatedproducts" title="{l s='Delete' mod='relatedproducts'}" data-pid="{$rec.id_related|escape:'htmlall':'UTF-8'}" data-ipa="{$rec.id_combination|escape:'htmlall':'UTF-8'}" data-current="{$id_current|escape:'htmlall':'UTF-8'}">{if $ps_version >= 1.6}<i class="icon-trash"></i>{else}<img src="{$smarty.const._PS_ADMIN_IMG_}delete.gif"/>
							{/if}&nbsp;{l s='Delete' mod='relatedproducts'}</a>
				    </td> 
				</tr>
			{/foreach}
		{/if}
		</tbody>	
	</table>
	<img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/line.gif" height="1" style="margin-top: 1px; width:100%">
	{if $ps_version >= 1.6}
	    <div class="panel-footer">
			<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='relatedproducts'}</a>
			<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='relatedproducts'}</button>
			<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='relatedproducts'}</button>
		</div>
	{/if}
</div>
