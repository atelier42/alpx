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
<div id="_desktop_language_selector">
  <div class="language-selector-wrapper">
{*    <span id="language-selector-label" class="hidden-md-up">{l s='Language:' d='Shop.Theme.Global'}</span>*}
    <div class="language-selector dropdown js-dropdown">
      <button data-toggle="dropdown" class="{*hidden-sm-down*} btn-unstyle" aria-haspopup="true" aria-expanded="false" aria-label="{l s='Language dropdown' d='Shop.Theme.Global'}">
        <span class="expand-more">{$language.iso_code}</span>
        <i class="material-icons expand-more">
{*            &#xE5C5;*}
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.70711 7.70711C7.31658 8.09763 6.68342 8.09763 6.29289 7.70711L0.292894 1.70711C-0.0976309 1.31658 -0.0976308 0.683416 0.292894 0.292892C0.683418 -0.0976326 1.31658 -0.0976326 1.70711 0.292892L7 5.58579L12.2929 0.292893C12.6834 -0.0976316 13.3166 -0.0976315 13.7071 0.292893C14.0976 0.683417 14.0976 1.31658 13.7071 1.70711L7.70711 7.70711Z" fill="white"/>
            </svg>
        </i>
      </button>

      <ul class="dropdown-menu {*hidden-sm-down*}" aria-labelledby="language-selector-label">
        {foreach from=$languages item=language}
          <li {if $language.id_lang == $current_language.id_lang} class="current" {/if}>
            <a href="{url entity='language' id=$language.id_lang}" class="dropdown-item" data-iso-code="{$language.iso_code}">{$language.name_simple}</a>
          </li>
        {/foreach}
      </ul>

{*      <select class="link hidden-md-up" aria-labelledby="language-selector-label">*}
{*        {foreach from=$languages item=language}*}
{*          <option value="{url entity='language' id=$language.id_lang}"{if $language.id_lang == $current_language.id_lang} selected="selected"{/if} data-iso-code="{$language.iso_code}">*}
{*            {$language.name_simple}*}
{*          </option>*}
{*        {/foreach}*}
{*      </select>*}
    </div>
  </div>
</div>
