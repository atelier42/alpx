{*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    FME Modules
*  @copyright © 2017 FME Modules
*  @version   1.3.1
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($style_sheet) AND !empty($style_sheet)}
{literal}<style type="text/css">{$style_sheet|escape:'htmlall':'UTF-8'}</style>{/literal}{/if}

{if isset($static_block) AND $static_block}
	<div id="static_content_wrapper">
		{foreach from=$static_block item=block}
			{if isset($block.id_static_block_template) AND $block.id_static_block_template AND isset($block.template) AND $block.template}
				{$block.template|regex_replace:"/[\r\t\n]/":" " nofilter}{* html content, cannot be escaped *}
			{else}
				<div {if $block.custom_css == 1} id="home_content_{$block.id_static_block|escape:'htmlall':'UTF-8'}" {else}id="home_content"{/if}>
				{if $block.title_active == 1}
					<h4 {if $block.custom_css == 1} id="mytitle_{$block.id_static_block|escape:'htmlall':'UTF-8'}"{else} id="mytitle"{/if} class="block" >
						{$block.block_title|escape:'htmlall':'UTF-8'}
					</h4>
				{/if}
				{$block.content|regex_replace:"/[\r\t\n]/":" " nofilter}{* html content, cannot be escaped *}
				</div>
			{/if}
		{/foreach}
	</div>
{/if}
