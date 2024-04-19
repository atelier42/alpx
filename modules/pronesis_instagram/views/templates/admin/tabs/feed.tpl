{*
* 2007-2023 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2023 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<h3><i class="icon-image"></i> {l s='Preview' mod='pronesis_instagram'} <small>{$module_display|escape:'html':'UTF-8'}</small></h3>
<div class="clearfix">
<a href={$request_uri|escape:'html':'UTF-8'}&current_view=feed&force_refresh=1" class="btn btn-default">{l s='Force refresh' mod='pronesis_instagram'}</a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">
{if $images}
{foreach $images as $image}
	{if $image.media_type == 'VIDEO'}
		<a data-fancybox="gallery" href="{$image.media_url}"><img src="{$image.thumbnail_url}" title="{if isset($image.caption)}{$image.caption}{/if}" height="100" width="100"></a>
	{elseif $image.media_type == 'IMAGE'}
		<a data-fancybox="gallery" href="{$image.media_url}"><img src="{$image.media_url}" title="{if isset($image.caption)}{$image.caption}{/if}" height="100" width="100"></a>
	{/if}
{/foreach}
{else}
	{l s='No feed! Please check Access Token.' mod='pronesis_instagram'}
{/if}
</div>