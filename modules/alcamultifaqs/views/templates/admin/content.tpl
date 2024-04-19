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
{if !$shopSelected}
  <p class="alert alert-warning">
    {l s='You must select a store to see the module options' mod='alcamultifaqs'}
  </p>
{else}

  <div id="alc_modules">
    <div id="pts_content" class="pts bootstrap nopadding clear clearfix">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="clearfix"></div>
          <div class="col-md-2 col-sm-12 col-xs-12">
            <div class="alc_link">
              <a href="https://addons.prestashop.com/{$alcalang_iso}/125_alcalink" title="Alcalink" target="_blank">
                <img src="{$alcalogo}" alt="Alcalink" />
              </a>
            </div>
            <ul class="nav menuizdo">
              <li class="active">
                <a href="#tab-form-faqform" data-toggle="tab" class="">
                  {l s='FAQ content' mod='alcamultifaqs'}
                </a>
              </li>
              <li>
                <a href="#tab-form-faqlist" data-toggle="tab" class="">
                  {l s='FAQs List' mod='alcamultifaqs'}
                </a>
              </li>
              <li>
                <a href="#tab-form-configuration" data-toggle="tab" class="">
                  {l s='Settings' mod='alcamultifaqs'}
                </a>
              </li>
            </ul>
            <ul class="alc_version">
              <li>
                {l s='Version' mod='alcamultifaqs'}: {$module_version|escape:'htmlall':'UTF-8'}<span
                  class="separator_bar">|</span>{l s='PrestaShop' mod='alcamultifaqs'}:
                {$ps_version|escape:'htmlall':'UTF-8'}
              </li>
            </ul>
          </div>

          <div class="col-md-10 col-sm-12 col-xs-12">
            <div class="panel pts-panel">
              <div class="panel-heading main-head">
                <span class="pts-content-current-tab">
                  {l s='FAQ everywhere' mod='alcamultifaqs'}
                </span>
              </div>
              <div class="panel-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab-form-faqform">
                    {$faqForm}
                  </div>
                  <div class="tab-pane" id="tab-form-faqlist">
                    <div class="selector-faq">
                      <div class="col-xs-12">
                        <div class="row alcamultifaqs-form-filter">
                          <div>
                            <span class="labeltxt">{l s='1. Filter FAQ location' mod='alcamultifaqs'}</span>
                            <select class="alcamultifaqs-filter-select-type fixed-width-xl" id="selector_faq_type"
                              name="selector_faq_type">
                              <option value="0">{l s='Select' mod='alcamultifaqs'}</option>
                              {foreach $alcamultifaqsFilter_type as $filter_type}
                                <option value="{$filter_type.id}">{$filter_type.name}</option>
                              {/foreach}
                            </select>
                          </div>
                          <div class="group-filter-item">
                            <span class="labeltxt">{l s='2. Filter item' mod='alcamultifaqs'}</span>
                            <select class="alcamultifaqs-filter-select-ids fixed-width-xl" id="selector_faq_ids"
                              name="selector_faq_ids">
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="alcabigloader">
                      <div class="alcaloaderaux"></div>
                      <span>Cargando lista...</span>
                    </div>
                    <div class="alcamultifaqs-content-faq-js">
                      {*include file="module:alcamultifaqs/views/templates/admin/alcamultifaqs-list.tpl" alcamultifaqsResult=$alcamultifaqsResult*}
                    </div>
                    <p class="text-muted small">
                      {l s='You can change the position of the elements by drag&drop (dragging with the cursor)' mod='alcamultifaqs'}
                    </p>

                  </div>
                  <div class="tab-pane" id="tab-form-configuration">
                    {$faqsettingsForm}

                    <h2>{l s='Read this if you are going to use custom hooks' mod='alcamultifaqs'}</h2>
                    <p class="alert alert-info">
                      {l s='With the exception of the "Home Page" or the "Footer", the other items must explicitly have a portion of code in order to display the created FAQ. Place the code in the template tpl files where you want to display each FAQ. Remember that the variables of each object must exist wherever you place it!' mod='alcamultifaqs'}
                    </p>

                    <h4>{l s='Product' mod='alcamultifaqs'}</h4>
                    <code>
                      &#123;hook h='displayAlcaMultiFaqProduct' product=$product&#125;
                    </code>
                    <hr />

                    <h4>{l s='Category' mod='alcamultifaqs'}</h4>
                    <code>
                      &#123;hook h='displayAlcaMultiFaqCategory' category=$category&#125;
                    </code>
                    <hr />

                    <h4>{l s='CMS Page' mod='alcamultifaqs'}</h4>
                    <code>
                      &#123;hook h='displayAlcaMultiFaqCMS' cms=$cms&#125;
                    </code>
                    <hr />

                    <h4>{l s='Manufacturer' mod='alcamultifaqs'}</h4>
                    <code>
                      &#123;hook h='displayAlcaMultiFaqManufacturer' manufacturer=$manufacturer&#125;
                    </code>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{/if}

<script type="text/javascript">
  var alcamultifaqsAdmAjaxUrl = "{$alcamultifaqsajax}";
  var alcamultifaqsTypeEdit = {if $alcamultifaqsType_edit}"{$alcamultifaqsType_edit}"{else}false{/if};
  var alcamultifaqsTxt_del = "{l s='Are you sure you want to remove this FAQ item?' mod='alcamultifaqs'}";
</script>