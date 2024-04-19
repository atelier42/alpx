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
<form action="{$admin_url}" method="post">
<div class="panel">
   <div class="panel-heading">
      
      {l s='Custom Hook' mod='staticblock'}
   </div>


   	<div class="alert alert-info">
  	<p>{l s='To use the custom hook you need to edit template file and add the custom hook where you want to show' mod='staticblock'}</p>
	</div>


   <div class="clearfix"></div>
   <div class="form-wrapper">
      <div class="form-group hide">
         <input type="hidden" name="id_custom_hook" id="id_custom_hook" value="">
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
        {l s='Title' mod='staticblock'}
         </label>
         <div class="col-lg-9">
            <input type="text" name="title_custom_hook" id="title_custom_hook" value="" class="" required="required">
           
         </div>
         <div class="clearfix"></div>
      </div>
      <div class="form-group">
         <label class="control-label col-lg-3 required">
         
         {l s='Hook Name:' mod='staticblock'}
         </label>
         <div class="col-lg-9">
            <input type="text" name="name_custom_hook" id="name_custom_hook" value="" class="" required="required">
            <p class="help-block"> </p>
            {l s='No space or special characters allowed - Example : displayCustomHook' mod='staticblock'}

         </div>
      </div>
   </div>
   <div class="clearfix"></div>
</br>
   {if $version < 1.7}
      <div class="alert alert-info">
         <p>{l s='In Prestashop 1.6 for dynamic hooks you must need to add new function in core file of module /modules/staticblock/staticblock.php ' mod='staticblock'}</p>
         

         <b>{l s='Note: replace NewHookName with name of new created hook' mod='staticblock'}</b>
         <p>
         </br>
            public function hook<b>NewHookName</b>($params)
         </br>
            {
         </br>
               &emsp;$hookBlocks = $this->getHookBlocks('<b>newHookName</b>');
         </br>
               &emsp;return $this->getBlock($hookBlocks, $params);
         </br>
            }
         </br>

         </br>

         </p>
      </div>

   {/if}
   
   <!-- /.form-wrapper -->
   <div class="panel-footer">
      <button  type="submit" name="savestaticblock_customhook" class="btn btn-default pull-right">
      <i class="process-icon-save"></i> {l s='Save' mod='staticblock'}
      </button>
      <a href="javascript:history.back()" class="btn btn-default"><i class="process-icon-back"></i>{l s='Back' mod='staticblock'}</a>
      
   </div>

   <div class="clearfix"></div>
</div></form>