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

{if isset($style_sheet) AND !empty($style_sheet)}
{literal}<style type = "text/css">{$style_sheet|escape:'htmlall':'UTF-8'}</style>{/literal}{/if}
{if $ps_17 > 0}<script type="text/javascript" src="{$jQuery_path|escape:'htmlall':'UTF-8'}"></script>{/if}
<script type="text/javascript">
$(document).ready(function() {
	$('.static_block_content').each(function() {
		var ids = $(this).attr('id').split('_');
		var id_static_block = ids[1];
		if (typeof static_blocks !== 'undefined' && static_blocks.length) {
			for (var i = 0; i < static_blocks.length; i++) {
				if (id_static_block == parseInt(static_blocks[i].id_static_block)) {
					if (parseInt(static_blocks[i].id_static_block_template) && static_blocks[i].template) {
						$(this).html(static_blocks[i].template);
					} else {
						$(this).html(static_blocks[i].content);
					}
				}
			}
		}
	});
});
</script>
