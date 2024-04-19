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
<div id="_desktop_cart">
  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header">
      {if $cart.products_count > 0}
        <a rel="nofollow" aria-label="{l s='Shopping cart link containing %nbProducts% product(s)' sprintf=['%nbProducts%' => $cart.products_count] d='Shop.Theme.Checkout'}" href="{$cart_url}">
      {/if}
{*        <i class="material-icons shopping-cart" aria-hidden="true">shopping_cart</i>*}
{*        <span class="hidden-sm-down">{l s='Cart' d='Shop.Theme.Checkout'}</span>*}
            <svg width="25" height="23" viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="Group 1350">
                    <path id="Vector" d="M24.2598 3.46412C23.7335 3.33861 23.181 3.63984 23.0494 4.14188L20.8655 12.3503H8.18306L5.18348 0.727967C5.07823 0.301228 4.68355 0 4.20994 0H0.99986C0.447306 0 0 0.426739 0 0.953888C0 1.48104 0.447306 1.90778 0.99986 1.90778H3.44688L7.49895 17.672C7.6042 18.0988 7.99888 18.4 8.4725 18.4H20.0235C20.5761 18.4 21.0234 17.9733 21.0234 17.4461C21.0234 16.919 20.5761 16.4922 20.0235 16.4922H9.23555L8.65668 14.2581H21.6022C22.0495 14.2581 22.4442 13.9569 22.5758 13.5553L24.9702 4.61883C25.1017 4.11678 24.786 3.58963 24.2598 3.46412Z" fill="#181818"/>
                    <path id="Vector_2" d="M10 23C11.1046 23 12 22.1762 12 21.16C12 20.1438 11.1046 19.32 10 19.32C8.89543 19.32 8 20.1438 8 21.16C8 22.1762 8.89543 23 10 23Z" fill="#181818"/>
                    <path id="Vector_3" d="M18 23C19.1046 23 20 22.1762 20 21.16C20 20.1438 19.1046 19.32 18 19.32C16.8954 19.32 16 20.1438 16 21.16C16 22.1762 16.8954 23 18 23Z" fill="#181818"/>
                </g>
            </svg>
        <span class="cart-products-count">
{*            (*}
            {$cart.products_count}
{*            )*}
        </span>
      {if $cart.products_count > 0}
        </a>
      {/if}
    </div>
  </div>
</div>
