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
<div class="col-lg-2">
{include file='./left_nav.tpl'}
</div>
<!-- List -->
<div class="col-lg-10">
 <div class="loader" style="display: none"></div>
	 <div id="staticblock_blocks" class="static_block_tab" style="display: none">
	 	{$renderBlockList}
	 </div>
	 <div id="staticblock_templates" class="static_block_tab" style="display: none">
	 	{$renderTemplateList}
	 </div>
	 <div id="staticblock_settings" class="static_block_tab" style="display: none">
	 	{$renderSettingsForm}
	 </div>
	 <div id="staticblock_reassurance" class="static_block_tab" style="display: none">
	 	{$renderReassuranceList}
	 </div>
	 <div id="staticblock_rsetting" class="static_block_tab" style="display: none">
	 	{$renderReassuranceSetting}
	 </div>
	 
	 <div id="staticblock_customhook" class="static_block_tab" style="display: none">
	 	{$renderCustomhook}
	 </div>
</div>
<div class="clearfix"></div>
