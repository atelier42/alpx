{*
 * 2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @author ALCALINK E-COMMERCE & SEO, S.L.L. <info@alcalink.com>
 * @copyright  2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Registered Trademark & Property of ALCALINK E-COMMERCE & SEO, S.L.L.
*}
{function name="alcamultifaqs_structure" faqopts=[]}
  {strip}
    {if $faqopts|count}

        {foreach from=$faqopts item=faq}
            <li data-order="{$faq.i}">
              <a href="#">{$faq.title}</a>
              <a class="btn btn-sm btn-default alcamultifaqs-btn-action" href="{$urlconfigModule}&edit_alcamultifaq={$faq.i}"><i class="icon-edit"></i></a>
              <button type="button" class="btn btn-sm btn-default alcamultifaqs-btn-action alcamultifaqs-delete" data-alcamultifaqs="{$faq.i}"><i class="icon-trash"></i></button>
            </li>

        {/foreach}
    {else}
    <div class="alert alert-warning">
      {l s='There are no FAQs created for the selected type/item' mod='alcamultifaqs'}
    </div>
    {/if}
  {/strip}
{/function}

<div id="alcamultifaqsstructure">
  <ul>
      {alcamultifaqs_structure faqopts=$alcamultifaqsResult}
  </ul>
</div>