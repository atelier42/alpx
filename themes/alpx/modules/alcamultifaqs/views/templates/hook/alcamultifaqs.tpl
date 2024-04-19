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
<div class="{if empty($alcamultifaqs)}empty-faq mt-3{/if}">
{if $alcamultifaqs|count}
<div class="alcamultifaqs-container{if $alcamultifaq_ps16_move} alcamultifaqs-move{/if}" id="alcamultifaq-{$alcamultifaq_type}">
    <p class="h4 alcamultifaq-title">
            {l s='FAQ' mod='alcamultifaqs'}
    </p>
        <div class="alcamultifaqs" itemscope="" itemtype="https://schema.org/FAQPage">
            {foreach from=$alcamultifaqs item=faq}
            <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h2 class="alcamultifaq-accordion" itemprop="name">
                    {$faq.title}
                </h2>
                <div class="alcamultifaq-panel" itemprop="suggestedAnswer acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                    <div itemprop="text">
                        {$faq.content nofilter}
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
{/if}
</div>