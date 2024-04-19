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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $smarty.const._PS_VERSION_ >= 1.6}
	<!-- Module content -->
	<div class="panel">
		<h3> {l s='Instagram Feed' mod='pronesis_instagram'}</h3>
		<p>
			<strong>{l s='Show your Instagram feed on your site!' mod='pronesis_instagram'}</strong><br />
		</p>
	</div>
	<div id="modulecontent" class="clearfix">
		<!-- Nav tabs -->
		<div class="col-lg-2">
			<div class="list-group left_menu">				
				<a href="{$request_uri|escape:'html':'UTF-8'}&current_view=config" class="list-group-item {if $current_view == 'config' or $current_view == ''}active{/if}"><i class="icon-wrench"></i> {l s='General configuration' mod='pronesis_instagram'}</a>
				<a href="{$request_uri|escape:'html':'UTF-8'}&current_view=feed" class="list-group-item {if $current_view == 'feed'}active{/if}"><i class="icon-image"></i> {l s='Feed Preview' mod='pronesis_instagram'}</a>
				<a href="{$request_uri|escape:'html':'UTF-8'}&current_view=help" class="list-group-item {if $current_view == 'help'}active{/if}"><i class="icon-book"></i> {l s='Help' mod='pronesis_instagram'}</a>
				<a href="{$request_uri|escape:'html':'UTF-8'}&current_view=logs" class="list-group-item {if $current_view == 'logs'}active{/if}"><i class="icon-gear"></i> {l s='Logs' mod='pronesis_instagram'}</a>
				<p>&nbsp;</p>
				<p class="text-center version_info"><i class="icon-info-circle"></i> {l s='Version' mod='pronesis_instagram'} {$module_version|escape:'html':'UTF-8'}</p>
			</div>
		</div>
		<!-- Tab panes -->
		<div class="tab-content col-lg-10">   
			<div class="tab-pane panel {if $current_view == 'config' or $current_view == ''}active{/if}" id="config">
                {include file="./tabs/config.tpl"}
			</div>
			<div class="tab-pane panel {if $current_view == 'feed'}active{/if}" id="feed">
                {include file="./tabs/feed.tpl"}
			</div>
			<div class="tab-pane panel {if $current_view == 'help'}active{/if}" id="help">
                {include file="./tabs/help.tpl"}
			</div>
			<div class="tab-pane panel {if $current_view == 'logs'}active{/if}" id="logs">
                {include file="./tabs/logs.tpl"}
			</div>
		</div>
	</div>
{else}
    <!-- Module content -->
	<div class="text-center header-container">
		<img src="{$module_dir|escape:'html':'UTF-8'}views/img/header_img.png" itemprop="logo" class="header_img">	
		<p class="text-center version_info"><i class="icon-info-circle"></i> {l s='Version' mod='pronesis_instagram'} {$module_version|escape:'html':'UTF-8'}</p>
	</div>
	<div id="modulecontent" class="clearfix">		
		<!-- Tab panes -->
		<div class="tab-content col-lg-10">   
			<div class="tab-pane panel" id="config">
                {include file="./tabs/config.tpl"}
			</div>
            <br/>
			<div class="tab-pane panel id="feed">
                {include file="./tabs/feed.tpl"}
			</div>			
            <br/>
            <div class="tab-pane panel" id="help">
                {include file="./tabs/help.tpl"}
			</div>
			<div class="tab-pane panel" id="help">
                {include file="./tabs/logs.tpl"}
			</div>
			<br/>
		</div>
	</div>
{/if}

