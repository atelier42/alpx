{*
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @category  FMM Modules
* @package   productlabelsandstickers
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{if !empty($stickers_banner.title)}
<div style="padding:10px 6px; margin:10px 0; text-align:center;background:{$stickers_banner.bg_color|escape:'htmlall':'UTF-8'};color:{$stickers_banner.color|escape:'htmlall':'UTF-8'};border:1px solid {$stickers_banner.border_color|escape:'htmlall':'UTF-8'};font-family:{$stickers_banner.font|escape:'htmlall':'UTF-8'};font-size:{$stickers_banner.font_size|escape:'htmlall':'UTF-8'}px;font-weight:{$stickers_banner.font_weight|escape:'htmlall':'UTF-8'};">
 {$stickers_banner.title|escape:'htmlall':'UTF-8'}
</div>
{/if}