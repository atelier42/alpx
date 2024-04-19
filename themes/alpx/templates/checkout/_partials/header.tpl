{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

{*
{block name='header_banner'}
  <div class="header-banner">
    <div class="container">
      {hook h='displayBanner'}
    </div>
  </div>
{/block}
*}

{block name='header_nav'}
  {*  <nav class="header-nav">*}
  {*    <div class="container">*}
  {*      <div class="row">*}
  {*        <div class="hidden-sm-down">*}
  {*          <div class="col-md-5 col-xs-12">*}
  {*            {hook h='displayNav1'}*}
  {*          </div>*}
  {*          <div class="col-md-7 right-nav">*}
  {*              {hook h='displayNav2'}*}
  {*          </div>*}
  {*        </div>*}
  {*        <div class="hidden-md-up text-sm-center mobile">*}
  {*          <div class="float-xs-left" id="menu-icon">*}
  {*            <i class="material-icons d-inline">&#xE5D2;</i>*}
  {*          </div>*}
  {*          <div class="float-xs-right" id="_mobile_cart"></div>*}
  {*          <div class="float-xs-right" id="_mobile_user_info"></div>*}
  {*          <div class="top-logo" id="_mobile_logo"></div>*}
  {*          <div class="clearfix"></div>*}
  {*        </div>*}
  {*      </div>*}
  {*    </div>*}
  {*  </nav>*}
{/block}


{block name='header_top'}
  <div class="header-top">
    <div class="container">
      <div class="row">
{*        <div id="menufullwidth" class="col-lg-5">*}
{*          {hook h='displayNavFullWidth'}*}
{*        </div>*}
        <div class="{*col-md-2 hidden-sm-down*}col-lg-12" id="_desktop_logo">
          {if $shop.logo_details}
            {if $page.page_name == 'index'}
              {*            <h1>*}
              {renderLogo}
              {*            </h1>*}
            {else}
              {renderLogo}
            {/if}
          {/if}
        </div>

{*        <div class="header-top-right *}{*col-md-10 col-sm-12*}{* position-static col-lg-5">*}
{*          {hook h='displayTop'}*}
{*        </div>*}
      </div>

{*      <div class="search-mobile-down">*}
{*        {hook h='displayNav'}*}
{*      </div>*}
    </div>
  </div>

{/block}