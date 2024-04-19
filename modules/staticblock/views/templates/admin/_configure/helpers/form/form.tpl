{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2018 FME Modules
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}
{block name="fieldset"}
	<div {if $right_column}class="col-lg-7"{/if}>
		{$smarty.block.parent}
	</div>
{/block}
{block name="other_fieldsets"}
	{if $right_column}
		<div class="col-lg-5">
			<div class="panel" id="conditions-panel">
				<h3><i class="icon-tasks"></i> {l s='Conditions' mod='staticblock'}</h3>
				<!-- categories -->
				{if isset($categories) AND $categories}
					<div class="form-group">
						<label for="id_category" class="control-label col-lg-2">{l s='Category' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_category_operator" name="id_category_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_category" name="id_category">
									{foreach from=$categories item='category'}
									<option value="{$category.id_category|intval}">({$category.id_category|intval}) {$category.name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_category">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}
				<!-- products -->
		        {if isset($products) AND $products}
					<div class="form-group">
						<label for="id_product" class="control-label col-lg-2">{l s='Product' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_product_operator" name="id_product_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_product" name="id_product">
									{foreach from=$products item='product'}
										<option value="{$product.id_product}">({$product.id_product}) {$product.name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_product">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}
				<!-- products price-->
				<div class="form-group">
					<label for="id_productprice" class="control-label col-lg-2">{l s='Product Price' mod='staticblock'}</label>
					<div class="col-lg-10">
						<div class="col-lg-3">
							<select id="id_productprice_operator" name="id_productprice_operator">
								<option value="equals">{l s='is' mod='staticblock'}</option>
								<option value="notequals">{l s='is not' mod='staticblock'}</option>
								<option value="greaterthan">{l s='greater than' mod='staticblock'}</option>
								<option value="lessthan">{l s='less than' mod='staticblock'}</option>
							</select>
						</div>
						<div class="col-lg-6">
							<input id="id_productprice" name="id_productprice">
						</div>
						<div class="col-lg-1">
							<a class="btn btn-default" href="javascript:void(0);" id="add_condition_productprice">
								<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
							</a>
						</div>
					</div>
				</div>
				<!-- products Quantity-->
				<div class="form-group">
					<label for="id_quantity" class="control-label col-lg-2">{l s='Product Quantity' mod='staticblock'}</label>
					<div class="col-lg-10">
						<div class="col-lg-3">
							<select id="id_quantity_operator" name="id_quantity_operator">
								<option value="equals">{l s='is' mod='staticblock'}</option>
								<option value="notequals">{l s='is not' mod='staticblock'}</option>
								<option value="greaterthan">{l s='greater than' mod='staticblock'}</option>
								<option value="lessthan">{l s='less than' mod='staticblock'}</option>
							</select>
						</div>
						<div class="col-lg-6">
							<input id="id_quantity" name="id_quantity">
						</div>
						<div class="col-lg-1">
							<a class="btn btn-default" href="javascript:void(0);" id="add_condition_quantity">
								<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
							</a>
						</div>
					</div>
				</div>
				<!-- cart products -->
		        {if isset($products) AND $products}
					<div class="form-group">
						<label for="id_cartproduct" class="control-label col-lg-2">{l s='Cart Product' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_cartproduct_operator" name="id_cartproduct_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_cartproduct" name="id_cartproduct">
									{foreach from=$products item='product'}
										<option value="{$product.id_product}">({$product.id_product}) {$product.name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_cartproduct">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}
				<!-- cart total -->
				<div class="form-group">
					<label for="id_carttotal_operator" class="control-label col-lg-2">{l s='Cart Total' mod='staticblock'}</label>
					<div class="col-lg-10">
						<div class="col-lg-3">
							<select id="id_carttotal_operator" name="id_carttotal_operator">
								<option value="equals">{l s='is' mod='staticblock'}</option>
								<option value="notequals">{l s='is not' mod='staticblock'}</option>
								<option value="greaterthan">{l s='greater than' mod='staticblock'}</option>
								<option value="lessthan">{l s='less than' mod='staticblock'}</option>
							</select>
						</div>
						<div class="col-lg-6">
							<input id="id_carttotal" name="id_carttotal">
						</div>
						<div class="col-lg-1">
							<a class="btn btn-default" href="javascript:void(0);" id="add_condition_carttotal">
								<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
							</a>
						</div>
					</div>
				</div>

				<!-- manufactureres -->
				{if isset($manufacturers) AND $manufacturers}
					<div class="form-group">
						<label for="id_manufacturer" class="control-label col-lg-2">{l s='Brand' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_manufacturer_operator" name="id_manufacturer_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_manufacturer" name="id_manufacturer">
									{foreach from=$manufacturers item='manufacturer'}
										<option value="{$manufacturer.id_manufacturer}">{$manufacturer.name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_manufacturer">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}
				
				<!-- Page Type -->
				{if isset($pages) AND $pages}
					<div class="form-group">
						<label for="id_page" class="control-label col-lg-2">{l s='Page Type' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_page_operator" name="id_page_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_page" name="id_page">
									{foreach from=$pages item='page'}
										<option value="{$page.id_meta|escape:'htmlall':'UTF-8'}">{$page.page|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_page">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}
				<!-- suppliers -->
				{if isset($suppliers) AND $suppliers}
					<div class="form-group">
						<label for="id_supplier" class="control-label col-lg-2">{l s='Supplier' mod='staticblock'}</label>
						<div class="col-lg-10">
							<div class="col-lg-3">
								<select id="id_supplier_operator" name="id_supplier_operator">
									<option value="equals">{l s='is' mod='staticblock'}</option>
									<option value="notequals">{l s='is not' mod='staticblock'}</option>
								</select>
							</div>
							<div class="col-lg-6">
								<select id="id_supplier" name="id_supplier">
									{foreach from=$suppliers item='supplier'}
										<option value="{$supplier.id_supplier}">{$supplier.name}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-lg-1">
								<a class="btn btn-default" href="javascript:void(0);" id="add_condition_supplier">
									<i class="icon-plus-sign"></i> {l s='Add Rule' mod='staticblock'}
								</a>
							</div>
						</div>
					</div>
				{/if}

			{if !$is_multishop}
				<input type="hidden" name="id_shop" value="1" />
			{/if}
			</div>
			<!-- add new rule group -->
			<a class="col-lg-12 btn btn-default" href="javascript:void(0);" id="add_condition_group">
				<i class="process-icon-new"></i> {l s='Add New Condition Group' mod='staticblock'}
			</a>

			<div class="clearfix">&nbsp;</div>
			<!-- rules condition list -->
			<div id="conditions">
				<div id="condition_group_list"></div>
			</div>
		</div>
	{/if}
			<div class="clearfix">&nbsp;</div>
{/block}

{block name="after"}
	{if $right_column}
		<script type="text/javascript">
		//<![CDATA[
			var temp_shops = [];
			var version = "{$smarty.const._PS_VERSION_|escape:'htmlall':'UTF-8'}";
			var id_static_block = parseInt("{if isset($smarty.get.id_static_block) && $smarty.get.id_static_block}{$smarty.get.id_static_block|escape:'htmlall':'UTF-8'}{else}{StaticblockModel::getLastId()}{/if}");
			// labels
			var embed_code_label = "{l s='Embedded Code' mod='staticblock' js=1}";
			var generate_label = "{l s='Generate' mod='staticblock' js=1}";
			var code_desc = "{l s='Copy this code and paste into any area to show the static block. Do not change the structure of the code.' mod='staticblock' js=1}";

			$(document).ready(function() {
				setTimeout(function(){
					if ($('#condition_group_list .condition_group').length <= 0)  {
						new_condition_group();
					}
				}, 300);				
				$('.advance-editor').summernote({
			        placeholder: 'Hello stand alone ui',
			        tabsize: 2,
			        height: 120,
			        toolbar: [
			          ['style', ['style']],
			          ['font', ['bold', 'underline', 'clear']],
			          ['color', ['color']],
			          ['para', ['ul', 'ol', 'paragraph','\n']],
			          ['view', ['fullscreen', 'codeview', 'help']],
			          ['table', ['table']],
			          ['insert', ['link', 'picture']]
			        ]
			    });
				var sel = "#fieldset_0";
				var ex_div = "";
				var ex_div_end = "";
				if(parseFloat(version) >= 1.6) {
					sel = "#static_block_form .form-wrapper";
					ex_div = '<div class="form-group margin-form">';
					ex_div_end = '</dv>';
				}
				if (id_static_block) {
					$(sel).append(
						ex_div
						+ '<label class="control-label col-lg-3">'+ embed_code_label +' : </label>'
						+ '<div class="col-lg-7 margin-form">'
						+ '<a class="btn btn-default button" onclick="generateCode('+ id_static_block +');"  href="javascript:void(0);">'
						+ '<i class="icon-code"></i> ' + generate_label + '</a>'
						+ '<br/><br/>'
						+ '<div id="snippet-code"></div><br/></div>'
						+ ex_div_end
					);
				}
				var shops = temp_shops.filter(function(elem, pos){
					return temp_shops.indexOf(elem) == pos;
				});

				setTimeout(function() {
		            $(".tree-item-name input[type=checkbox]").each(function(){
		                $(this).prop("checked", false);
		                $(this).removeClass("tree-selected");
		            });

		            for (var i = 0; i < shops.length; i++) {
		            	$('input:checkbox[name="checkBoxShopAsso_configuration[' + shops[i] + ']"]').prop("checked", true);
		            }
		        },800);

		        if (parseFloat(version) < 1.6) {
		        	if($("input:radio[name=custom_css]:checked").val() == 0) {
		                $("#css").parent().hide();
		                $("#css").parent().prev("label").hide();
		            }
		            $("input:radio[name=custom_css]").click(function() {
		                if($(this).val() == 0) {
		                    $("#css").parent().hide();
		                    $("#css").parent().prev("label").hide();
		                } else {
		                    $("#css").parent().show();
		                    $("#css").parent().prev("label").show();
		                }
		            });
		        } else {
		            if($("input:radio[name=custom_css]:checked").val() == 0)
		                $("#css").parent().parent().hide();
		            $("input:radio[name=custom_css]").click(function(){
		                if($(this).val() == 0) {
		                    $("#css").parent().parent().hide();
		                } else {
		                    $("#css").parent().parent().show();
		                }
		            });
		        }
		    });

			function generateCode(id_static_block) {
				var code = $('<textarea rows="5" cols="10"><div id="static-block-wrapper_' + id_static_block + '" class="static_block_content"></div></textarea><p class="help-block preference_description">' + code_desc + '</p>'
					);
				$("#snippet-code").html(code);
			}
		//]]>
		</script>
		{literal}
		<style type="text/css">
			.selectable_row {cursor: pointer;}
			.selectable_row:hover { background: #DCF4F9; }
		</style>
		{/literal}
	{/if}
{/block}

{block name="script"}
	$('#static_block_form, #staticblock_settings').each(function(e){
		var selectElements = $(this).find('select');
		selectElements.css('width', '100%').select2();
	})
	{if $right_column}
		var current_id_condition_group = 0;
		var last_condition_group = 0;
		var conditions = new Array();

		function toggle_condition_group(id_condition_group)
		{
			$('.condition_group').removeClass('alert-info');
			$('.condition_group > table').removeClass('alert-info');
			$('#condition_group_'+id_condition_group+' > table').addClass('alert-info');
			$('#condition_group_'+id_condition_group).addClass('alert-info');
			current_id_condition_group = id_condition_group;
		}

		function add_condition(id_condition_group, type, value, operator)
		{
			var id_condition = id_condition_group+'_'+type+'_'+operator+'_'+value;
			if (typeof conditions[id_condition] != 'undefined')
				return false;
			var condition = new Array();
			condition.type = type;
			condition.value = value;
			condition.operator = operator;
			condition.id_condition_group = id_condition_group;
			conditions[id_condition] = condition;
			return id_condition;
		}

		function delete_condition(condition)
		{
			delete conditions[condition];

			to_delete = $('#'+condition).prev();
			if ($(to_delete).children().hasClass('btn_delete_condition'))
				$(to_delete).remove();
			else
				$('#'+condition).next().remove();

			$('#'+condition).remove();
			return false;
		}

		function delete_condition_group(condition_group)
		{
			var previousSiblings = $('#' + condition_group).prevAll('.condition_separator');
			var nextSiblings = $('#' + condition_group).nextAll('.condition_separator');
			if (previousSiblings.length) {
				$(previousSiblings[0]).remove();
			} else if (nextSiblings.length) {
				$(nextSiblings[0]).remove();
			}

			$('#' + condition_group + ' .condition_row').each(function(e){
				delete_condition($(this).attr('id'))
			})
			$('#' + condition_group).remove();

		}

		function new_condition_group()
		{
			$('#conditions-panel').show();
			var html = '';

			if (last_condition_group > 0)
				html += '<div class="cseprator row condition_separator text-center initialism"><strong class="form-control">{l s='or' mod='staticblock' js=1}</strong></div><div class="cseprator  clearfix">&nbsp;</div>';

			last_condition_group++;
			html += '<div id="condition_group_'+last_condition_group+'" class="panel condition_group alert-info"><h3><i class="icon-tasks"></i> {l s='Condition group' mod='staticblock' js=1} '+last_condition_group+'<a class="pull-right btn btn-danger button" href="javascript:void(0);" onclick="delete_condition_group(\'condition_group_'+last_condition_group+'\');"><i class="icon-remove"></i></a></h3>';
				html += '<table class="table alert-info"><thead><tr class="selectable_row"><th class="fixed-width-md"><span class="title_box">{l s='Type' mod='staticblock' js=1}</span></th><th><span class="title_box">{l s='Operator' mod='staticblock' js=1}</span></th><th><span class="title_box">{l s='Value' mod='staticblock' js=1}</span></th><th></th></tr></thead><tbody></tbody></table>';
				html += '</div>';
			$('#condition_group_list').append(html);
			toggle_condition_group(last_condition_group);
		}

		function appendConditionToGroup(html)
		{
			if ($('#condition_group_'+current_id_condition_group+' table tbody tr').length > 0)
				$('#condition_group_'+current_id_condition_group+' table tbody').append('<tr><td class="text-center btn_delete_condition initialism" colspan="4"><b>{l s='and' mod='staticblock' js=1}</b></td></tr>');
			$('#condition_group_'+current_id_condition_group+' table tbody').append(html);
		}

		$(document).ready(function() {
			$('#leave_bprice_on').click(function() {
				if (this.checked)
					$('#price').attr('disabled', 'disabled');
				else
					$('#price').removeAttr('disabled');
			});
			var selectedOpt = $('#select-editor option:selected').val();
			{if $smarty.const._PS_VERSION_ >= '1.7.0.0'}
				if (selectedOpt == 1) {
					$('.classic-editor').parent().parent().parent().parent().hide();
					$('.basic-editor').parent().parent().parent().parent().show();
					$('.advance-editor').parent().parent().parent().parent().hide();
					$('.code-editor').parent().parent().parent().parent().hide();
				} else if (selectedOpt == 2) {
					$('.basic-editor').parent().parent().parent().parent().hide();
					$('.code-editor').parent().parent().parent().parent().hide();
					$('.advance-editor').parent().parent().parent().parent().hide();
					$('.classic-editor').parent().parent().parent().parent().show();
				} else if (selectedOpt == 3) {
					$('.basic-editor').parent().parent().parent().parent().hide();
					$('.code-editor').parent().parent().parent().parent().hide();
					$('.advance-editor').parent().parent().parent().parent().show();
					$('.classic-editor').parent().parent().parent().parent().hide();
				} else {
					$('.basic-editor').parent().parent().parent().parent().hide();
					$('.classic-editor').parent().parent().parent().parent().hide();
					$('.advance-editor').parent().parent().parent().parent().hide();
					$('.code-editor').parent().parent().parent().parent().show();
				}
			{else}
				if (selectedOpt == 1) {
					$('.classic-editor').parent().parent().hide();
					$('.basic-editor').parent().parent().show();
					$('.advance-editor').parent().parent().hide();
					$('.code-editor').parent().parent().hide();
				} else if (selectedOpt == 2) {
					$('.basic-editor').parent().parent().hide();
					$('.code-editor').parent().parent().hide();
					$('.advance-editor').parent().parent().hide();
					$('.classic-editor').parent().parent().show();
				} else if (selectedOpt == 3) {
					$('.basic-editor').parent().parent().hide();
					$('.code-editor').parent().parent().hide();
					$('.advance-editor').parent().parent().show();
					$('.classic-editor').parent().parent().hide();
				} else {
					$('.basic-editor').parent().parent().hide();
					$('.classic-editor').parent().parent().hide();
					$('.advance-editor').parent().parent().hide();
					$('.code-editor').parent().parent().show();
				}
			{/if}
			$('#static_block_form').live('submit', function(e) {
				var html = '';
				for (i in conditions)
					html += '<input type="hidden" name="condition_group_'+conditions[i].id_condition_group+'[]" value="'+conditions[i].type+'_'+conditions[i].operator+'_'+conditions[i].value+'" />';
				$('#conditions').append(html);
			});

			$('#id_feature').change(function() {
				$('.id_feature_value').hide();
				$('#id_feature_'+$(this).val()).show();
			});

			$('#id_product').change(function() {
				$('.id_product').hide();
				$('#id_product_'+$(this).val()).show();
			});

			$('#add_condition_category').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'category', $('#id_category option:selected').val(), $('#id_category_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Category' mod='staticblock' js=1}</td><td>'+$('#id_category_operator option:selected').html()+'</td><td>'+$('#id_category option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_product').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'product', $('#id_product option:selected').val(), $('#id_product_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Product' mod='staticblock' js=1}</td><td>'+$('#id_product_operator option:selected').html()+'</td><td>'+$('#id_product option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_productprice').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'productprice', $('#id_productprice').val(), $('#id_productprice_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Product Price' mod='staticblock' js=1}</td><td>'+$('#id_productprice_operator option:selected').html()+'</td><td>'+$('#id_productprice').val()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';				
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_quantity').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'quantity', $('#id_quantity').val(), $('#id_quantity_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Product Quantity' mod='staticblock' js=1}</td><td>'+$('#id_quantity_operator option:selected').html()+'</td><td>'+$('#id_quantity').val()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				
				appendConditionToGroup(html);
				return false;
			});

			$('#add_condition_cartproduct').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'cartproduct', $('#id_cartproduct option:selected').val(), $('#id_product_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Cart Product' mod='staticblock' js=1}</td><td>'+$('#id_cartproduct_operator option:selected').html()+'</td><td>'+$('#id_cartproduct option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_carttotal').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'carttotal', $('#id_carttotal').val(), $('#id_carttotal_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Cart Total' mod='staticblock' js=1}</td><td>'+$('#id_carttotal_operator option:selected').html()+'</td><td>'+$('#id_carttotal').val()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_manufacturer').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'manufacturer', $('#id_manufacturer option:selected').val(), $('#id_manufacturer_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Brand' mod='staticblock' js=1}</td><td>'+$('#id_manufacturer_operator option:selected').html()+'</td><td>'+$('#id_manufacturer option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_cgroups').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'cgroups', $('#id_cgroups option:selected').val(), $('#id_cgroups_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Customer' mod='staticblock' js=1}</td><td>'+$('#id_cgroups_operator option:selected').html()+'</td><td>'+$('#id_cgroups option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_page').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'page', $('#id_page option:selected').val(), $('#id_page_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Page Type' mod='staticblock' js=1}</td><td>'+$('#id_page_operator option:selected').html()+'</td><td>'+$('#id_page option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_supplier').click(function() {
				var id_condition = add_condition(current_id_condition_group, 'supplier', $('#id_supplier option:selected').val(), $('#id_supplier_operator option:selected').val());
				if (!id_condition)
					return false;

				var html = '<tr class="condition_row" id="'+id_condition+'"><td>{l s='Supplier' mod='staticblock' js=1}</td><td>'+$('#id_supplier_operator option:selected').html()+'</td><td>'+$('#id_supplier option:selected').html()+'</td><td><a href="javascript:void(0);" onclick="delete_condition(\''+id_condition+'\');" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='staticblock' js=1}</a></td></tr>';
				appendConditionToGroup(html);

				return false;
			});

			$('#add_condition_group').click(function() {
				new_condition_group();
				return false;
			});

			$('.condition_group').live('click', function() {
				var id = this.id.split('_');
				toggle_condition_group(id[2]);
				return false;
			});

			{foreach from=$conditions key='id_group_condition' item='condition_group'}
				new_condition_group();
				{foreach from=$condition_group item='condition'}
					{if $condition.type == 'carttotal' OR $condition.type == 'productprice' OR $condition.type == 'quantity'}
						$('#id_{$condition.type}').val({$condition.value});
						$('#id_{$condition.type}_operator option[value="{$condition.operator}"]').attr('selected', true);
					{else}
						$('#id_{$condition.type} option[value="{$condition.value}"]').attr('selected', true);
						$('#id_{$condition.type}_operator option[value="{$condition.operator}"]').attr('selected', true);
					{/if}
					$('#add_condition_{$condition.type}').click();
				{/foreach}
			{/foreach}
			$('#id_product').change();
			$('#id_feature').change();


		});
	{/if}
	{if isset($codemirror) && $codemirror}
		var code = $('#template-code')[0];
		var editor = CodeMirror.fromTextArea(code, {
			lineNumbers: true,
			mode:  "htmlmixed",
			theme : "{$theme}",
			tabSize: 2,
			showCursorWhenSelecting:true,
			viewportMargin: Infinity,
			styleActiveLine: true,
			matchBrackets: true,
			matchTags: {
				bothTags: true
			},
			extraKeys: {
				'Ctrl-E' : 'autocomplete'
			},
			value: document.documentElement.innerHTML
		});
	{/if}
	{if isset($codemirrors) && $codemirrors}
		var langs = {$langs};
		$.each( langs, function( i, lang){
		  	var code = $('#codes-editor_'+lang['id_lang'])[0];
			var editor = CodeMirror.fromTextArea(code, {
				mode:  "htmlmixed",
				theme : "{$theme}",
				tabSize: 2,
				showCursorWhenSelecting:true,
				viewportMargin: Infinity,
				styleActiveLine: true,
				matchBrackets: true,
				matchTags: {
					bothTags: true
				},
				extraKeys: {
					'Ctrl-E' : 'autocomplete'
				},
				value: document.documentElement.innerHTML
			});
		});
	{/if}
	{if $smarty.const._PS_VERSION_ >= '1.7.0.0'} 
		$('#select-editor').change(function(){
			var selectedOpt = $("#select-editor option:selected").val();
			if (selectedOpt == 1) {
				$('.classic-editor').parent().parent().parent().parent().hide();
				$('.basic-editor').parent().parent().parent().parent().show();
				$('.advance-editor').parent().parent().parent().parent().hide();
				$('.code-editor').parent().parent().parent().parent().hide();
			} else if (selectedOpt == 2) {
				$('.basic-editor').parent().parent().parent().parent().hide();
				$('.code-editor').parent().parent().parent().parent().hide();
				$('.advance-editor').parent().parent().parent().parent().hide();
				$('.classic-editor').parent().parent().parent().parent().show();
			} else if (selectedOpt == 3) {
				$('.basic-editor').parent().parent().parent().parent().hide();
				$('.code-editor').parent().parent().parent().parent().hide();
				$('.advance-editor').parent().parent().parent().parent().show();
				$('.classic-editor').parent().parent().parent().parent().hide();
			} else {
				$('.basic-editor').parent().parent().parent().parent().hide();
				$('.classic-editor').parent().parent().parent().parent().hide();
				$('.advance-editor').parent().parent().parent().parent().hide();
				$('.code-editor').parent().parent().parent().parent().show();
			}
		});
	{else}
		$('#select-editor').change(function(){
			var selectedOpt = $("#select-editor option:selected").val();
			if (selectedOpt == 1) {
				$('.classic-editor').parent().parent().hide();
				$('.basic-editor').parent().parent().show();
				$('.advance-editor').parent().parent().hide();
				$('.code-editor').parent().parent().hide();
			} else if (selectedOpt == 2) {
				$('.basic-editor').parent().parent().hide();
				$('.code-editor').parent().parent().hide();
				$('.advance-editor').parent().parent().hide();
				$('.classic-editor').parent().parent().show();
			} else if (selectedOpt == 3) {
				$('.basic-editor').parent().parent().hide();
				$('.code-editor').parent().parent().hide();
				$('.advance-editor').parent().parent().show();
				$('.classic-editor').parent().parent().hide();
			} else {
				$('.basic-editor').parent().parent().hide();
				$('.classic-editor').parent().parent().hide();
				$('.advance-editor').parent().parent().hide();
				$('.code-editor').parent().parent().show();
			}
		});
	{/if}
{/block}