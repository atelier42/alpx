{*
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FMM Modules
*  @copyright 2021 FME Modules
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{if $ps_version <= 0}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
{/if}

{extends file="helpers/form/form.tpl"}

    {block name="input"}
        {if $input.name == 'rules'}
            <div class="col-lg-5">
                <table class="table table-bordered panel">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <span class="title_box">{l s='Related Products Rules' mod='relatedproducts'}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    {if isset($rules) AND $rules}
                    {* {$rules|@print_r} *}
                        {foreach from=$rules item=rule}
                            <tr>
                                <td>
                                    <input type="radio" value="{$rule.id_related_products_rules|escape:'htmlall':'UTF-8'}" id="groupBox_{$rule.id_related_products_rules|escape:'htmlall':'UTF-8'}" class="groupBox" name="groupBox" {if isset($selected_rules) AND $selected_rules AND $selected_rules AND ($rule.id_related_products_rules == $selected_rules)}checked="checked"{/if} {if ($rule.id_page == 1 || $rule.id_page == 2 || $rule.id_page == 5)} disabled="disabled" {/if}>
                                </td>
                                <td>
                                    <label for="groupBox_{$rule.id_related_products_rules|escape:'htmlall':'UTF-8'}">{$rule.titles|escape:'htmlall':'UTF-8'}</label>
                                </td>
                            </tr>
                        {/foreach}
                    {else}
                        <tr>
                            <td style="color:red;">
                                {l s='*First add rules' mod='relatedproducts'}
                            </td>
                        </tr>
                    {/if}
                    </tbody>
                </table>
            </div>
        {elseif $input.name == 'criteria'}
            <div class="col-lg-5">
                <table class="table table-bordered panel">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <span class="title_box">{l s='Where to display products' mod='relatedproducts'}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    {if isset($criteria) AND $criteria}
                        {foreach from=$criteria item=rule}
                        <tr>
                            <td>
                                <input type="radio" value="{$rule.id_criteria|escape:'htmlall':'UTF-8'}" id="criteria_{$rule.id_criteria|escape:'htmlall':'UTF-8'}" class="criteria" name="criteria" {if isset($selected_criteria) AND $selected_criteria AND $selected_criteria AND ($rule.id_criteria == $selected_criteria)}checked="checked"{/if}>
                            </td>
                            <td>
                                <label for="criteria_{$rule.id_criteria|escape:'htmlall':'UTF-8'}">{$rule.name|escape:'htmlall':'UTF-8'}</label>
                            </td>
                        </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
            </div>
        {elseif $input.name == 'specific_cat'}
            <div id="specific_cat_trees">
                {$category_tree}
            </div>
        {elseif $input.name == 'specific_prod'}
            <div id="specific_prod_tree">
                <div class="col-lg-8">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-search"></i>
                        </span>
                        <input type="text" placeholder="Example: Blue XL shirt" onkeyup="getRelProducts(this);" />
                    </div>
                    
                    <div id="rel_holder"></div>
                    <div id="rel_holder_temp">
                        <ul>
                            {if (!empty($products))}
                            {foreach from=$products item=product}
                            <li id="row_{$product->id|escape:'htmlall':'UTF-8'}" class="media"><div class="media-left"><img src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'home_default')|escape:'htmlall':'UTF-8'}" class="media-object image"></div><div class="media-body media-middle"><span class="label">{$product->name|escape:'htmlall':'UTF-8'}&nbsp;(ID:{$product->id|escape:'htmlall':'UTF-8'})</span><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="{$product->id|escape:'htmlall':'UTF-8'}" name="related_products[]"></li>
                            {/foreach}
                            {/if}
                        </ul>
                    </div>
                </div>
            </div>
        {else}
            {$smarty.block.parent}
        {/if}
    {/block}
{block name="other_input"}
{literal}
<script type="text/javascript">
var mod_url = "{/literal}{$action_url}{literal}";
$(document).ready(function(){
    hideallNoOfPro();
    var id = $('.criteria:checked').val();
    if (id == 1) {
        $('#specific_cat_trees').show();
    } else if(id == 2){
        $('#specific_prod_tree').closest('.form-group').show();
    }
});
function hideallNoOfPro()
{
    $('#specific_cat_trees').hide();
    $('#specific_prod_tree').closest('.form-group').hide();
}
$('.criteria').change(function(){
    var id = $(this).val();
    if(this.checked) {
        if (id == 1) {
            $('#specific_cat_trees').show();
            $('#specific_prod_tree').closest('.form-group').hide();
           
        } else if(id == 2) {
            $('#specific_prod_tree').closest('.form-group').show();
            $('#specific_cat_trees').hide();
           
        } else if(id == 3){
            $('#specific_prod_tree').closest('.form-group').hide();
            $('#specific_cat_trees').hide();
            
        } else {
            $('#specific_prod_tree').closest('.form-group').hide();
            $('#specific_cat_trees').hide();
           
        }
    }
})
function getRelProducts(e) {
  var search_q_val = $(e).val();

  //controller_url = controller_url+'&q='+search_q_val;
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

function relClearData() {
    $('#rel_holder').html('');
}
function relDropThis(e) {
    $(e).parent().parent().remove();
}
function relSelectThis(id, ipa, name, img) {
  
  if ($('#row_' + id).length > 0) {
    return false;
  }
    if ($('#row_' + id + '_' + ipa).length > 0) {
    showErrorMessage(error_msg);
  } else {
    var draw_html = '<li id="row_' + id + '" class="media"><div class="media-left"><img src="'+img+'" class="media-object image"></div><div class="media-body media-middle"><span class="label">'+name+'&nbsp;(ID:'+id+')</span><i onclick="relDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+id+'" name="related_products[]"></li>'
    $('#rel_holder_temp ul').append(draw_html);
  }
}
</script>
<style type="text/css">
.rcg_max_height { max-height: 600px; overflow-y: scroll}
.help-block b { color: red;}
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
#rel_holder_temp ul li .media-left { width: 8%}
#rel_holder_temp ul li .media-left img { max-width: 100%}
#rel_holder_temp ul li .media-body { width: 86%; margin-left: 5%}
#rel_holder_temp ul li .media-body span { float: left; font-size: 13px; color: #6c868e; font-weight: normal; white-space: normal !important;
text-align: left; width: 92%}
#rel_holder_temp ul li .media-body i { float: right; cursor: pointer}
.placeholder_holder { position: relative}
.ps_16_specific .material-icons {display:inline;font-size: 1px;color: #fff;}
.ps_16_specific .material-icons::before {content: "\f00d"; font-family: "FontAwesome"; font-size: 25px;text-align: center;
color: red;font-style: normal; text-indent: -9999px; font-weight: normal; line-height: 20px;}
</style>
{/literal}
{/block}