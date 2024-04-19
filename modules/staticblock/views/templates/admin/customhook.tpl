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

<div class="panel col-lg-12">
	<div class="panel-heading">

		{l s='Custom Hook' mod='staticblock'}

		<span class="panel-heading-action">
			<a class="list-toolbar-btn" href="{$admin_url}">
				<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add New" data-html="true" data-placement="top">
					<i class="process-icon-new"></i>
				</span>
			</a>
		</span>

	<div class="col-lg-12">
   <table id="table-module-staticblock" class="table tableDnD staticblock">
      <thead>
         <tr class="nodrag nodrop">
            <th class=" center">
               <span class="title_box">
               {l s='ID' mod='staticblock'}
               </span>
            </th>
            <th class="">
               <span class="title_box">
               {l s='Title' mod='staticblock'}
               </span>
            </th>
            <th class="">
               <span class="title_box">
               {l s='Hook' mod='staticblock'}
               </span>
            </th>
            <th class="">
               <span class="title_box">
               {l s='Action' mod='staticblock'}
               </span>
            </th>
         </tr>
      </thead>
      <tbody>
      	{if $all_data}
      	{foreach from=$all_data item=tag}
         <tr>
            <td class="center">
               {$tag.id_static_block_hook}
            </td>
            <td>
               {$tag.hook_title}
            </td>
            <td>
               {$tag.hook_name}
            </td>
            <td>
                <div class="btn-group">
                <a onclick="return confirm('Are you sure you want to delete this?');" href="{$delete_rec}&id={$tag.id_static_block_hook}" title="delete" class="delete btn btn-default">
   				<i class="icon-ban-circle"></i>{l s='Delete' mod='staticblock'}</a>
            	</div>
            </td>
         </tr>
         {/foreach}
         {else}
         <tr>
         </tr>
         {/if}
      </tbody>
   </table>
</div>

	</div>
</div>
