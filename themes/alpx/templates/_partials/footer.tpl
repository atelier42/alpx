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

{if $page.page-name == category}
  <div class="container">
    <div class="seo-category">
      {$totcustomfields_display_seo_category nofilter}
    </div>
  </div>
{/if}

<div class="container">
  <div class="row-news">
    {block name='hook_footer_before'}
      {hook h='displayFooterBefore'}
    {/block}
    <div class="icon-footer">
      {hook h='displayNewsImage'}
    </div>
  </div>
</div>


<div class="footer-container">
  <div class="container">
    <div class="row">
      {block name='hook_footer'}
        {hook h='displayFooter'}
      {/block}
    </div>
  </div>
</div>


<div class="footer-icones">
  <div class="container">
    <div class="row">
      {block name='hook_footer_after'}
        {hook h='displayFooterAfter'}
      {/block}
    </div>
    <div class="row bottom-page">
      <div class="col-sm-4">
        <p class="copyright">
          {block name='copyright_link'}
            <a href="https://www.prestashop.com" target="_blank" rel="noopener noreferrer nofollow">
              {l s='%copyright% %year% - Ecommerce software by %prestashop%' sprintf=['%prestashop%' => 'PrestaShop™', '%year%' => 'Y'|date, '%copyright%' => '©'] d='Shop.Theme.Global'}
            </a>
          {/block}
        </p>
      </div>
      <div class="col-sm-8 icon-footer">
        {hook h='displayCustomIcon'}
      </div>
    </div>
  </div>
</div>
{*sixtrone modal CGV  *}
<div class="modalx modal fade js-checkout-modal" id="modal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
        <span aria-hidden="true"><i class="material-icons">close</i></span>

      </button>
      <div class="js-modal-content">

      </div>
    </div>
  </div>
</div>