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
{extends file="helpers/form/form.tpl"}

{block name="input"}
{if $input.name == 'specific_cat'}
  <div id="specific_cat_tree">
    {$category_tree}
  </div> 
{elseif $input.name == 'specific_pro'}
  <div id="specific_pro_tree" class="col-lg-7">
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
{elseif $input.name == 'tagsproduct'}
  <div id="tagslabel" class="col-lg-9">
      <input type="text" name="tagsvalue" value="{if isset($tags_value)}{$tags_value|escape:'htmlall':'UTF-8'}{/if}" data-role="tagsinput" id="tagsvalue_9">
      <p class="hint help-block">{l s='Press comma or enter after writing tags name' mod='relatedproducts'}</p>
  </div>
{else}
    {$smarty.block.parent}
{/if}
{/block}

{block name="input"}
{if $input.name == 'page'}
  
    <div class="form-group">
          <label class="control-label col-sm-2" style="margin-left: -140px;">
              <span title="" data-html="true">{l s='Select Page' mod='relatedproducts'}</span>
          </label>
        <div class="row">
            <div class="input-group col-lg-5">
              <div class="{if $ps_version >= 1.6}row{/if}">
                  <div class="col-lg-12">
                      <table class="table table-bordered panel">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th>
                                      <span class="title_box">{l s='Page Name' mod='relatedproducts'}</span>
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                          {if isset($pages) AND $pages}
                              {foreach from=$pages item=page}
                              <tr>
                                  <td>
                                      <input type="radio" onclick="checkedPage(this);" value="{$page.id_page|escape:'htmlall':'UTF-8'}" id="pageBox_{$page.id_page|escape:'htmlall':'UTF-8'}" class="pageBox" name="pageBox" {if isset($selectedRules) AND $selectedRules AND $selectedRules AND ($page.id_page == $selectedRules['id_page'])}checked="checked"{/if}>
                                  </td>
                                  <td>
                                      <label for="pageBox_{$page.id_page|escape:'htmlall':'UTF-8'}">{$page.name|escape:'htmlall':'UTF-8'}</label>
                                  </td>
                              </tr>
                              {/foreach}
                          {/if}
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
    </div>

  {else}
      {$smarty.block.parent}
{/if}
{/block}

{block name="input"}
{if $input.name == 'pages_cms'}
    <div class="form-group" id="cms-pages">
      <label class="control-label col-sm-2" style="margin-left: -140px;">
          <span title="" data-html="true">{l s='Select CMS Page' mod='relatedproducts'}</span>
      </label>
      <div class="col-lg-5">
          <div class="{if $ps_version >= 1.6}row{/if}">
              <div class="col-lg-12">
                  <table class="table table-bordered panel">
                      <thead>
                          <tr>
                              <th></th>
                              <th>
                                  <span class="title_box">{l s='Page Name' mod='relatedproducts'}</span>
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                      {if isset($pages_cms) AND $pages_cms}
                          {foreach from=$pages_cms item=page_cms}
                          <tr>
                              <td>
                                  <input type="checkbox" value="{$page_cms.id_cms|escape:'htmlall':'UTF-8'}" id="cms_pageBox_{$page_cms.id_cms|escape:'htmlall':'UTF-8'}" class="pageBox" name="cms_pageBox[]" {if isset($selectedRules) AND $selectedRules AND $selectedRules AND in_array($page_cms.id_cms, ','|explode:$selectedRules['id_cms_pages'])}checked="checked"{/if}>
                              </td>
                              <td>
                                  <label for="cms_pageBox_{$page_cms.id_cms|escape:'htmlall':'UTF-8'}">{$page_cms.meta_title|escape:'htmlall':'UTF-8'}</label>
                              </td>
                          </tr>
                          {/foreach}
                      {/if}
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
    </div>
{else}
  {$smarty.block.parent}
{/if}
{/block}



{block name="input"}
    {if $input.name == 'group'}
        <div class="col-lg-7">
            <table class="table table-bordered panel">
                <thead>
                    <tr>
                        <th></th>
                        <th>
                            <span class="title_box">{l s='Rule Type' mod='relatedproducts'}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                {if isset($rules) AND $rules}
                    {foreach from=$rules item=rule}
                    <tr>
                        <td>
                            <input type="radio" value="{$rule.id_group|escape:'htmlall':'UTF-8'}" id="groupBox_{$rule.id_group|escape:'htmlall':'UTF-8'}" class="groupBox" name="groupBox" {if isset($selectedRules) AND $selectedRules AND $selectedRules AND ($rule.id_group == $selectedRules['id_rules'])}checked="checked"{/if}>
                        </td>
                        <td>
                            <label for="groupBox_{$rule.id_group|escape:'htmlall':'UTF-8'}">{$rule.name|escape:'htmlall':'UTF-8'}</label>
                        </td>
                    </tr>
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
{block name="other_input"}
<script type="text/javascript">
      function checkedPage(e) {
        var id_page = e.value;
        checkedPageFunc(id_page)

      }

      function checkedPageFunc(id_page) {
          $('#groupBox_1, #groupBox_2, #groupBox_3, #groupBox_4, #groupBox_5, #groupBox_6, #groupBox_7, #groupBox_8').prop("disabled", false);
          
        if(id_page == 1 || id_page == 2 || id_page == 5) {
          $('#groupBox_5, #groupBox_6, #groupBox_7, #groupBox_8').prop("disabled", true);
        }

        if(id_page != 5) {
          $('#cms-pages').hide();
        } else{
          $('#cms-pages').show();
        }

      }

    $(document).ready(function()
    {
        hideallNoOfPro();

      // **** Mange pages Rules hide and show **** //

        var id_page = $("input[name='pageBox']:checked").val();
        checkedPageFunc(id_page);

        var select_cat = $('#cat_pro_opt option:selected').val();
        if (select_cat == 0) {
          $('#cats').hide();
          $('#spec_pro').hide();
        } else if(select_cat == 1)  {
          $('#cats').show();
          $('#spec_pro').hide();
        } else {
          $('#spec_pro').show();
          $('#cats').hide();
        }
        $('.groupBox:radio:checked').each(function(){
          var val = $(this).val();
          if (val ==9) {
            //$('#tagsvalue_9').parent().parent().show();
            $('#tagslabel').closest('.form-group').show();
            $('#no_pro').show();
            $('#no_pro').parent().parent().show();
            $('#cat_pro_opt').show();
            $('#cat_pro_opt').parent().parent().show();
          }
          else if (val ==10) {
            $('#specific_cat_tree').show();
            $('#no_pro').show();
            $('#no_pro').parent().parent().show();
            $('#cat_pro_opt').show();
            $('#cat_pro_opt').parent().parent().show();
          }
          else if (val ==11) {
            $('#specific_pro_tree').closest('.form-group').show();
            $('#specific_cat_tree').hide();
            $('#no_pro').hide();
            $('#no_pro').parent().parent().hide();
            //$('#spec_pro').hide();
            $('#cat_pro_opt').show();
            $('#cat_pro_opt').parent().parent().show();
          }
          else {
            $('#no_pro').parent().parent().show();
            $('#no_pro').show();
            $('#cat_pro_opt').show();
            $('#cat_pro_opt').parent().parent().show();
          }
        });
    $('#cat_pro_opt').change(function (){
      var vals = $(this).val();
      if (vals == 0) {
        $('#cats').hide();
        $('#spec_pro').hide();
      } else if(vals == 1) {
        $('#cats').show();
        $('#spec_pro').hide();

      } else {
        $('#spec_pro').show();
        $('#cats').hide();
      }
    });
    $(".groupBox").change(function() {
      var id ="no_pro";
      var id_g ="groupBox_"+$(this).val();
      var ids = "cat_pro_opt";
      var cat_id = "cats";
      if(this.checked) {
        var select_cat = $('#cat_pro_opt option:selected').val();
        if (select_cat == 0) {
          $('#cats').hide();
          $('#spec_pro').hide();
        } else if(select_cat == 1)  {
          $('#cats').show();
          $('#spec_pro').hide();
        } else {
          $('#spec_pro').show();
          $('#cats').hide();
        }
        if (id_g=='groupBox_10')
        {
          //$('#tagsvalue_9').parent().parent().hide();
          $('#tagslabel').closest('.form-group').hide();
          $('#'+ids).show();
          $('#'+ids).parent().parent().show();
          //$('#'+cat_id).show();
          $('#specific_cat_tree').show();
          $('#specific_pro_tree').closest('.form-group').hide();
          $('#no_pro').show();
          $('#no_pro').parent().parent().show();
          $('#cat_pro_opt').show();
          $('#cat_pro_opt').parent().parent().show();
          //$('#spec_pro').hide();
        }
        else if (id_g=='groupBox_11')
        {
          //$('#tagsvalue_9').parent().parent().hide();
          $('#tagslabel').closest('.form-group').hide();
          $('#'+ids).show();
          $('#'+ids).parent().parent().show();
          //$('#'+cat_id).show();
          $('#specific_pro_tree').closest('.form-group').show();
          $('#specific_cat_tree').hide();
          $('#no_pro').hide();
          $('#no_pro').parent().parent().hide();
          $('#cat_pro_opt').show();
          $('#cat_pro_opt').parent().parent().show();
          //$('#spec_pro').hide();
        }
        else if (id_g=='groupBox_9')
        {
          $('#specific_cat_tree').hide();
          $('#specific_pro_tree').closest('.form-group').hide();
          //$('#tagsvalue_9').parent().parent().show();
          $('#tagslabel').closest('.form-group').show();
          $('#'+id).parent().parent().show();
          $('#'+id).show();
          $('#'+ids).show();
          $('#'+ids).parent().parent().show();
          $('#specific_cat_tree').hide();
          $('#specific_pro_tree').closest('.form-group').hide();
        } else {
          //$('#tagsvalue_9').parent().parent().hide();
          $('#tagslabel').closest('.form-group').hide();
          $('#'+id).parent().parent().show();
          $('#'+id).show();
          $('#'+ids).show();
          $('#'+ids).parent().parent().show();
          $('#specific_cat_tree').hide();
          $('#specific_pro_tree').closest('.form-group').hide();
        }
      } else {
        var select_cat = $('#cat_pro_opt option:selected').val();
        if (select_cat == 0) {
          $('#cats').hide();
          $('#spec_pro').hide();
        } else if(select_cat == 1)  {
          $('#cats').show();
          $('#spec_pro').hide();
        } else {
          $('#cats').hide();
          $('#spec_pro').show();
        }
        $('#'+id).hide();
        $('#'+id).parent().parent().hide();
        $('#'+ids).hide();
        $('#'+ids).parent().parent().hide();
        $('#specific_cat_tree').hide();
        $('#specific_pro_tree').closest('.form-group').hide();
         if (id=='groupBox_9') {
            $('#specific_cat_tree').hide();
            $('#specific_pro_tree').closest('.form-group').hide();
            //$('#tagsvalue_9').parent().parent().show();
            $('#tagslabel').closest('.form-group').show();
            $('#'+id).parent().parent().show();
            $('#'+id).show();
            $('#'+ids).show();
            $('#'+ids).parent().parent().show();
            $('#specific_cat_tree').hide();
            $('#specific_pro_tree').closest('.form-group').hide();

         }
         if (id_g=='groupBox_10') {
          $('#specific_cat_tree').hide();
          $('#'+ids).show();
          $('#'+ids).parent().parent().show();
          //$('#'+cat_id).show();
          $('#no_pro').show();
          $('#no_pro').parent().parent().show();
          $('#cat_pro_opt').show();
          $('#cat_pro_opt').parent().parent().show();
         }
         if (id_g=='groupBox_11') {
          $('#specific_pro_tree').closest('.form-group').hide();
          $('#no_pro').hide();
          $('#no_pro').parent().parent().hide();
          $('#'+ids).hide();
          $('#'+ids).parent().parent().hide();
          $('#cat_pro_opt').show();
          $('#cat_pro_opt').parent().parent().show();
         }
      }
    });
    });
    function hideallNoOfPro()
    {
      $('#no_pro').hide();
      $('#no_pro').parent().parent().hide();
      $('#tagslabel').closest('.form-group').hide();
      $('#cat_pro_opt').hide();
      $('#cat_pro_opt').parent().parent().hide();
      $('#specific_cat_tree').hide();
      $('#specific_cat').hide();
      $('#specific_pro_tree').closest('.form-group').hide();
      $('#cats').hide();
      $('#spec_pro').hide();
      $('#specific_pro').hide();
      //$('#tagsvalue_9').parent().parent().hide();
    }
var mod_url = "{$action_url}";
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

function getSpecProducts(e) {
  var search_q_val = $(e).val();
  var mod_urls = "{$action_url}";
  //controller_url = controller_url+'&q='+search_q_val;
  if (typeof search_q_val !== 'undefined' && search_q_val) {
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: mod_urls + '&q=' + search_q_val,
      success: function(data)
      {
        var quicklink_list ='<li class="rel_breaker" onclick="specClearData();"><i class="material-icons">&#xE14C;</i></li>';
        $.each(data, function(index,value){
          if (typeof data[index]['id'] !== 'undefined')
            quicklink_list += '<li onclick="specSelectThis('+data[index]['id']+','+data[index]['id_product_attribute']+',\''+data[index]['name']+'\',\''+data[index]['image']+'\');"><img src="' + data[index]['image'] + '" width="60"> ' + data[index]['name'] + '</li>';
        });
        if (data.length == 0) {
          quicklink_list = '';
        }
        $('#rel_holders').html('<ul>'+quicklink_list+'</ul>');
      },
      error : function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
      }
    });
  }
  else {
    $('#rel_holders').html('');
  }
}

function specClearData() {
    $('#rel_holders').html('');
}
function specDropThis(e) {
    $(e).parent().parent().remove();
}
function specSelectThis(id, ipa, name, img) {
  if ($('#rows_' + id).length > 0) {
    return false;
  }
  if ($('#rows_' + id + '_' + ipa).length > 0) {
    showErrorMessage(error_msg);
  } else {
    var draw_html = '<li id="rows_' + id + '" class="media"><div class="media-left"><img src="'+img+'" class="media-object image"></div><div class="media-body media-middle"><span class="label">'+name+'&nbsp;(ID:'+id+')</span><i onclick="specDropThis(this);" class="material-icons delete">clear</i></div><input type="hidden" value="'+id+'" name="spec_products[]"></li>'
    $('#rel_holder_temps ul').append(draw_html);
  }
}
</script>
{literal}
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
.ps_16_specific .material-icons {font-size: 1px;color: #fff;}
.ps_16_specific .material-icons::before {content: "\f00d"; font-family: "FontAwesome"; font-size: 25px;text-align: center;
color: red;font-style: normal; text-indent: -9999px; font-weight: normal; line-height: 20px;}
</style>{/literal}

{/block}
