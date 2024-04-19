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
<style>
#rel_holder ul { position: absolute; left: 12px; border-radius: 4px; top: 40px; margin: 0px 0 20%; padding: 0; background: #fff;
border: 1px solid #BBCDD2; z-index: 999}
#rel_holder ul li { list-style: none; padding: 5px 10px; display: block; margin: 0px}
#rel_holder ul li:hover { cursor: pointer; background: #25B9D7}
#rel_holder ul li.rel_breaker { padding: 0px; margin: -1px -22px 0 0; background: #fff; float: right;border: 1px solid #BBCDD2;
 border-left: 0px; height: 24px;}
#rel_holder ul li.rel_breaker:hover { background: #fff;}
.rel_breaker i {font-size: 22px; color: #E50B70; cursor: pointer}
#rel_holder_temp { clear: both; padding: 10px 0}
#rel_holder_temp ul { padding: 0; margin: 0}
#rel_holder_temp ul li { list-style: none; padding: 3px 5px; border-radius: 5px; margin: 6px 0; border: 1px solid #E5E5E5;
display: block}
#rel_holder_temp ul li div { display: inline-block; vertical-align: middle}
#rel_holder_temp ul li .media-left { width: 18%}
#rel_holder_temp ul li .media-left img { max-width: 100%}
#rel_holder_temp ul li .media-body { width: 77%; margin-left: 5%}
#rel_holder_temp ul li .media-body span { float: left; font-size: 13px; color: #6c868e; font-weight: normal}
#rel_holder_temp ul li .media-body i { float: right; cursor: pointer}
</style>
<script type="text/javascript">
var success_msg		= "{/literal}{l s='Product successfully added to list' mod='relatedproducts' js=1}{literal}";
var error_msg		= "{/literal}{l s='Product already added to the list' mod='relatedproducts' js=1}{literal}";
var delete_label	= "{/literal}{l s='Delete' mod='relatedproducts' js=1}{literal}";
var img_path		= "{/literal}{$smarty.const._PS_ADMIN_IMG_|escape:'htmlall':'UTF-8'}{literal}";
var version 		= "{/literal}{$ps_version|escape:'htmlall':'UTF-8'}{literal}";
var controller_url = "{/literal}{$base_admin_url|escape:'htmlall':'UTF-8'}/ajax_products_list.php?forceJson=1&disableCombination=0&exclude_packs=0&excludeVirtuals=0&limit=20{literal}";
var mod_url = "{/literal}{$action_url nofilter}{literal}";
function getRelProducts(e) {
	var search_q_val = $(e).val();
	controller_url = controller_url+'&q='+search_q_val;
	if (typeof search_q_val !== 'undefined' && search_q_val) {
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: mod_url + '&q=' + search_q_val,
			success: function(data)
			{
				var quicklink_list ='<li class="rel_breaker" onclick="relClearData();"><i class="material-icons">&#xE14C;</i></li>';
				$.each(data, function(index,value){
					if (typeof data[index]['id'] !== 'undefined')
						quicklink_list += '<li onclick="relSelectThis('+data[index]['id']+','+data[index]['id_product_attribute']+',\''+data[index]['name']+'\',\''+data[index]['image']+'\');"><img src="' + data[index]['image'] + '" width="60"> ' + data[index]['name'] + '</li>';
				});
				if (data.length == 0) {
					quicklink_list = '';
				}
				$('#rel_holder').html('<ul>'+quicklink_list+'</ul>');
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});
	}
	else {
		$('#rel_holder').html('');
	}
}

function relSelectThis(id, ipa, name, img) {
	if ($('#row_' + id + '_' + ipa).length > 0) {
		showErrorMessage(error_msg);
	} else {
	  var draw_html = '<li id="row_' + id + '_' + ipa + '" class="media"><div class="media-left"><img src="'+img+'" class="media-object image"></div><div class="media-body media-middle"><span class="label">'+name+'</span><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+ipa+'" name="related_products[]['+id+']"></li>'
	  $('#rel_holder_temp ul').append(draw_html);
	}
}

$('document').ready(function()
{
	var link = jQuery('.spy').val();
	var lang = jQuery('.lang_spy').val();
});

function relClearData() {
    $('#rel_holder').html('');
}
function relDropThis(e) {
    $(e).parent().parent().remove();
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

</script>
{/literal} 

<div class="panel">
	<h3 class="panel-heading">{l s='Related Products' mod='relatedproducts'}</h3> 
	<div class="form-group">   
		<label class="col-lg-3 control-label form-control-label" style="padding-left: 4%">{l s='Search a product' mod='relatedproducts'}</label>
		<div class="col-lg-7" style="position: relative">
			    <input name="product_selected" type="text" id="rel_product_name" onclick="relClearData();" class="form-control search typeahead tt-input" onkeyup="getRelProducts(this);" autocomplete="off"/>
			    <input class="spy" type="hidden" value="{$link->getPageLink('search')|escape:'htmlall':'UTF-8'}" />
			    <input class="lang_spy" type="hidden" value="{$id_lang|escape:'htmlall':'UTF-8'}" />
				<div id="rel_holder"></div>
				<div id="rel_holder_temp"><ul></ul></div>
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
