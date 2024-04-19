<?php
/**
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
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

include 'classes' . DIRECTORY_SEPARATOR . 'AlcaMultiFaq.php';

class AlcaMultiFaqs extends Module
{
    private $_html = '';
    private $ajaxController = 'AdminAlcaMultiFaqsActions';
    private $configValues = [
        'ALCAMULTIFAQS_CUSTOMHOOK_CATEGORY' => 0,
        'ALCAMULTIFAQS_CUSTOMHOOK_CMS' => 0,
        'ALCAMULTIFAQS_CUSTOMHOOK_MANUFACTURER' => 0,
        'ALCAMULTIFAQS_CUSTOMHOOK_PRODUCT' => 0,
    ];
    private $templateFiles;
    public $faqTypes;
    public $isps17;

    public function __construct()
    {
        $this->name = 'alcamultifaqs';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'ALCALINK E-COMMERCE & SEO, S.L.L.';
        $this->module_key = '620d75bb3ba6dc200d500175bf4d8597';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('FAQ everywhere');
        $this->description = $this->l('Frequently Asked Questions on Home, footer, products, categories and manufacturers');

        $this->ps_versions_compliancy = ['min' => '1.6.1.0', 'max' => _PS_VERSION_];
        $this->isps17 = version_compare(_PS_VERSION_, '1.7.0.0', '>=');

        $this->faqTypes = [
            'home' => $this->l('Homepage'),
            'footer' => $this->l('Footer'),
            'category' => $this->l('Category'),
            'product' => $this->l('Product'),
            'cms' => $this->l('CMS Page'),
            'manufacturer' => $this->l('Manufacturer'),
        ];

        if ($this->isps17) {
            $this->templateFiles = 'module:alcamultifaqs/views/templates/hook/alcamultifaqs.tpl';
        } else {
            $this->templateFiles = $this->local_path . 'views/templates/hook/alcamultifaqs.tpl';
        }
    }

    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';

        return parent::install() &&
        $this->installTab() &&
        $this->installConfigs() &&
        $this->registerHook('displayBackOfficeHeader') &&
        $this->registerHook('displayHeader') &&
        $this->registerHook('displayHomeCustom') &&
        ($this->isps17 ? $this->registerHook('displayFooterBefore') : $this->registerHook('displayFooter')) &&
        $this->registerHook('displayContentWrapperBottom') &&
        $this->registerHook('displayAlcaMultiFaqCategory') &&
        $this->registerHook('displayAlcaMultiFaqProduct') &&
        $this->registerHook('displayAlcaMultiFaqCMS') &&
        $this->registerHook('displayAlcaMultiFaqManufacturer');
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';

        foreach ($this->configValues as $key) {
            if (!Configuration::deleteByName($key)) {
                return false;
            }
        }

        return parent::uninstall() && $this->uninstallTab();
    }

    public function installConfigs()
    {
        $all_shops = Shop::getShops();

        foreach ($all_shops as $shop) {
            $id_shop = (int) $shop['id_shop'];
            $id_shop_group = (int) $shop['id_shop_group'];

            foreach ($this->configValues as $key => $defaultvalue) {
                Configuration::updateValue($key, $defaultvalue, false, $id_shop_group, $id_shop);
            }
        }

        return true;
    }

    /**
     * Crear tab invisible para controlador ajax
     *
     * @return bool
     */
    public function installTab()
    {
        $tab = new Tab();
        $tab->class_name = $this->ajaxController;
        $tab->id_parent = 0;
        $tab->module = $this->name;

        if (!$this->isps17) {
            $tab->active = 0;
        }

        $languages = Language::getLanguages();

        foreach ($languages as $language) {
            $tab->name[$language['id_lang']] = $this->ajaxController;
        }

        $tab->add();

        return true;
    }

    /**
     * Elimina tab creado en instalación
     *
     * @return bool
     */
    public function uninstallTab()
    {
        $idTab = Tab::getIdFromClassName($this->ajaxController);

        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
        }

        return true;
    }

    /**
     * Procesos del módulo. Create / edit.
     *
     * @return void
     */
    public function postProcess()
    {
        if (Tools::isSubmit('submitFaq')) {
            $id_alcamultifaqs = (int) Tools::getValue('id_alcamultifaqs');

            if (!empty($id_alcamultifaqs) && $id_alcamultifaqs > 0) {
                $faq = new AlcaMultiFaq($id_alcamultifaqs);
            } else {
                $faq = new AlcaMultiFaq();
            }

            $faq->hydrate(Tools::getAllValues());

            $lastPos = AlcaMultiFaq::getLastPosition($faq->id_alcamultifaqs, $faq->type, $faq->id_object);
            $faq->position = $lastPos + 1;

            $id_shop = $this->context->shop->id;
            $faq->id_shop = (int) $id_shop;
            $languages = Language::getLanguages();
            $haveErrors = false;

            foreach ($languages as $lang) {
                $haveErrors = false;
                $title = Tools::getValue('title_' . $lang['id_lang']);
                $content = Tools::getValue('content_' . $lang['id_lang']);

                if (empty($title) && empty($content)) {
                    $haveErrors = true;

                    $this->_html .= $this->displayError(
                        $this->l('Missing to enter content for language: ') .
                        strtoupper(Language::getIsoById((int) $lang['id_lang']))
                    );
                } else {
                    $faq->title[$lang['id_lang']] = (!empty($title) ? $title : null);
                    $faq->content[$lang['id_lang']] = (!empty($content) ? $content : null);
                }
            }

            if ($haveErrors) {
                return $this->_html;
            } else {
                $this->clearCache();
                $this->clearCacheObject($faq);
                $faq->save();

                $this->_html .= $this->displayConfirmation($this->l('Saved successfully'));
            }
        }

        if (Tools::isSubmit('submitSettingFaq')) {
            // actualizamos config.
            foreach ($this->configValues as $key => $defaultvalue) {
                $value = Tools::getValue($key);
                Configuration::updateValue($key, $value);
            }

            $this->clearCache();
            $this->_html .= $this->displayConfirmation($this->l('Saved successfully'));
        }

        return '';
    }

    public function clearCacheObject($faq)
    {
        if ($faq->type) {
            $suffix_object = '';

            if ((int) $faq->id_object != 0) {
                $suffix_object = '|' . $faq->id_object;
            }

            if ($this->isps17) {
                $template = $this->templateFiles;
            } else {
                $pos = strrpos($this->templateFiles, '/');
                $template = substr($this->templateFiles, $pos + 1);
            }

            $cacheId = $this->name . '|' . $faq->type . $suffix_object;
            $this->_clearCache($template, $cacheId);
        }
    }

    public function clearCache()
    {
        if ($this->isps17) {
            $template = $this->templateFiles;
        } else {
            $pos = strrpos($this->templateFiles, '/');
            $template = substr($this->templateFiles, $pos + 1);
        }

        $this->_clearCache($template, $this->getCacheId($this->name));
        $this->_clearCache('*');
    }

    /**
     * Contenido principal del módulo. Carga de formularios
     *
     * @return void
     */
    public function getContent()
    {
        $this->_html = '';
        $this->postProcess();

        $id_lang = $this->context->language->id;
        $alcamultifaqsType_edit = null;
        $shopSelected = true;

        if (Shop::isFeatureActive()) {
            if (Shop::getContext() !== Shop::CONTEXT_SHOP) {
                $shopSelected = false;
            }
        }

        if ($id = (int) Tools::getValue('edit_alcamultifaq')) {
            $alcamultifaq = new AlcaMultiFaq($id);

            if ($alcamultifaq) {
                $alcamultifaqsType_edit = $alcamultifaq->type;
            }
        }

        $this->context->smarty->assign(
            [
                'isps17' => $this->isps17,
                'alcamultifaqsType_edit' => $alcamultifaqsType_edit,
                'alcamultifaqsFilter_type' => $this->getValuesSelect('type'),
                'alcamultifaqsFilter_ids' => $this->getValuesSelect('ids_type'),
                'faqForm' => $this->renderForm(),
                'faqsettingsForm' => $this->renderSettingsForm(),
                'urlconfigModule' => $this->context->link->getAdminLink('AdminModules', true)
                . '&configure=' . $this->name . '&module_name=' . $this->name,
                'alcamultifaqsajax' => $this->context->link->getAdminLink($this->ajaxController),
                'shopSelected' => $shopSelected,
                'module_version' => $this->version,
                'ps_version' => _PS_VERSION_,
                'alcalogo' => Tools::getHttpHost(true) . __PS_BASE_URI__ . '/modules/' . $this->name . '/views/img/logo.png',
                'alcalang_iso' => Language::getIsoById((int) $id_lang),
            ]
        );

        $this->_html .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/content.tpl');

        return $this->_html;
    }

    /**
     * Recibir info para edición
     *
     * @return array
     */
    public function getEditFields()
    {
        $languages = Language::getLanguages(false);
        $fields = [];

        if ($id = (int) Tools::getValue('edit_alcamultifaq')) {
            $alcamultifaq = new AlcaMultiFaq($id);

            if ($alcamultifaq) {
                foreach ($alcamultifaq as $k => $l) {
                    $fields[$k] = $l;
                }

                foreach ($languages as $lang) {
                    $fields['title'][$lang['id_lang']] = $alcamultifaq->title[$lang['id_lang']];
                    $fields['content'][$lang['id_lang']] = $alcamultifaq->content[$lang['id_lang']];
                }

                return $fields;
            }
        }

        if (Tools::isSubmit('submitFaq')) {
            $fields = [
                'id_alcamultifaqs' => '',
                'type' => Tools::getValue('type'),
                'id_object' => Tools::getValue('id_object'),
            ];

            foreach ($languages as $lang) {
                $fields['title'][$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
                $fields['content'][$lang['id_lang']] = Tools::getValue('content_' . $lang['id_lang']);
            }
        } else {
            $fields = [
                'id_alcamultifaqs' => '',
                'type' => '',
                'id_object' => '',
            ];

            foreach ($languages as $lang) {
                $fields['title'][$lang['id_lang']] = '';
                $fields['content'][$lang['id_lang']] = '';
            }
        }

        return $fields;
    }

    /**
     * Construir options de los selects en el Form del backoffice
     *
     * @param string $field
     *
     * @return array
     */
    public function getValuesSelect($field)
    {
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        $result = [];

        switch ($field) {
            case 'type':
                $x = 0;

                foreach ($this->faqTypes as $typeid => $typename) {
                    $result[$x]['id'] = $typeid;
                    $result[$x]['name'] = $typename;
                    ++$x;
                }

                break;
            case 'ids_type':
                if ($id = (int) Tools::getValue('edit_alcamultifaq')) {
                    $alcamultifaq = new AlcaMultiFaq($id);

                    if ($alcamultifaq) {
                        $result = AlcaMultiFaq::getIdsByType($alcamultifaq->type);
                    }
                } else {
                    $root_category_id = 2;
                    $category_tree = Category::getAllCategoriesName($root_category_id, $id_lang);

                    foreach ($category_tree as $y => $c) {
                        $result[$y]['id'] = $c['id_category'];
                        $result[$y]['name'] = $c['name'];
                    }
                }

                break;
        }

        return $result;
    }

    /**
     * Formulario principal del módulo para creación/edición
     *
     * @return array
     */
    public function renderForm()
    {
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;

        $editingAlcaMultiFaqs = false;
        $titleForm = $this->l('Add FAQ content');
        $butForm = $this->l('Add');

        if ($editingAlcaMultiFaqs = (int) Tools::getValue('edit_alcamultifaq')) {
            $titleForm = $this->l('Edit FAQ content');
            $butForm = $this->l('Update');
        }

        $fieldsForm = [
            'form' => [
                'id' => 'alcamultifaq',
                'legend' => [
                    'title' => $titleForm,
                    'icon' => 'icon-' . ($editingAlcaMultiFaqs ? 'edit' : 'plus'),
                ],
                'input' => [
                    [
                        'type' => 'hidden',
                        'name' => 'id_alcamultifaqs',
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Where is the FAQ placed?'),
                        'name' => 'type',
                        'class' => 'alcamultifaqs-type',
                        'required' => true,
                        'options' => [
                            'query' => $this->getValuesSelect('type'),
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Add to...'),
                        'name' => 'id_object',
                        'class' => 'alcamultifaqs-id-object',
                        'options' => [
                            'query' => $this->getValuesSelect('ids_type'),
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('FAQ Title'),
                        'name' => 'title',
                        'class' => 'alcamultifaqs-title',
                        'required' => true,
                        'lang' => true,
                    ],
                    [
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('FAQ Content'),
                        'class' => 'alcamultifaqs-content',
                        'name' => 'content',
                        'cols' => 8,
                        'rows' => 4,
                        'autoload_rte' => 'rte',
                    ],
                ],
                'submit' => [
                    'title' => $butForm,
                    'class' => 'btn btn-primary pull-right',
                    'icon' => 'process-icon-' . ($editingAlcaMultiFaqs ? 'edit' : 'plus'),
                ],
            ],
        ];

        if ($editingAlcaMultiFaqs) {
            $fieldsForm['form']['buttons'] = [
                '0' => [
                    'type' => 'button',
                    'title' => $this->l('Cancel edit'),
                    'name' => 'alcamultifaqsCancel',
                    'icon' => 'process-icon-cancel',
                    'class' => 'btn btn-default pull-right',
                    'href' => $this->context->link->getAdminLink('AdminModules', true)
                    . '&configure=' . $this->name . '&module_name=' . $this->name,
                ],
            ];
        }

        $defaultLang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $defaultLang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
        ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFaq';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getEditFields(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fieldsForm]);
    }

    /**
     * Formulario de configuración
     *
     * @return array
     */
    public function renderSettingsForm()
    {
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;

        $fieldsForm = [
            'form' => [
                'id' => 'alcasticker',
                'legend' => [
                    'title' => $this->l('FAQ Location Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('FAQ of categories in custom hook'),
                        'name' => 'ALCAMULTIFAQS_CUSTOMHOOK_CATEGORY',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('If custom hook selected it will use "displayAlcaMultiFaqCategory" hook'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('FAQ of CMS Pages in custom hook'),
                        'name' => 'ALCAMULTIFAQS_CUSTOMHOOK_CMS',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('If custom hook selected it will use "displayAlcaMultiFaqCMS" hook'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('FAQ of manufacturers in custom hook'),
                        'name' => 'ALCAMULTIFAQS_CUSTOMHOOK_MANUFACTURER',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('If custom hook selected it will use "displayAlcaMultiFaqManufacturer" hook'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('FAQ of products in custom hook'),
                        'name' => 'ALCAMULTIFAQS_CUSTOMHOOK_PRODUCT',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('If custom hook selected it will use "displayAlcaMultiFaqProduct" hook'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-primary pull-right',
                    'icon' => 'process-icon-save',
                ],
            ],
        ];

        $defaultLang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $defaultLang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
        ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettingFaq';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFields(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fieldsForm]);
    }

    /**
     * Recibir info para formulario de configuración
     *
     * @return array
     */
    public function getConfigFields()
    {
        $fields = [];

        foreach ($this->configValues as $key => $defaultvalue) {
            $fields[$key] = Tools::getValue($key, Configuration::get($key));
        }

        return $fields;
    }

    /**
     * Carga de CSS/JS en el Front
     *
     * @return void
     */
    public function hookDisplayHeader()
    {
        if ($this->isps17) {
            $this->context->controller->registerStylesheet(
                $this->name,
                'modules/' . $this->name . '/views/css/' . $this->name . '.css',
                ['media' => 'all', 'priority' => 200]
            );

            $this->context->controller->registerJavascript(
                'modules-' . $this->name . '-javascript',
                'modules/' . $this->name . '/views/js/' . $this->name . '.js',
                ['media' => 'all', 'priority' => 150]
            );
        } else {
            $this->context->controller->addCss($this->_path . 'views/css/' . $this->name . '.css');
            $this->context->controller->addJS($this->_path . 'views/js/' . $this->name . '16.js');
        }
    }

    /**
     * Carga de CSS en el Back
     *
     * @return void
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (
            Tools::getValue('configure') == $this->name &&
            $this->context->controller instanceof AdminModulesController
        ) {
            $this->context->controller->addCss($this->_path . 'views/css/back.css');
            $this->context->controller->addCss($this->_path . 'views/css/' . $this->name . '_adm.css');
            $this->context->controller->addJquery();
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJqueryPlugin('select2');
            $this->context->controller->addJS($this->_path . 'views/js/' . $this->name . '_adm.js');

            Media::addJsDef(
                [
                    'alcamultifaq_selected_type' => Tools::getValue('type') ? Tools::getValue('type') : '',
                    'alcamultifaq_selected_id_object' => Tools::getValue('id_object') ? Tools::getValue('id_object') : '',
                    'alcamultifaq_token' => Tools::getAdminTokenLite('AdminProducts'),
                    'alcamultifaq_alert_product_exists' => $this->l('This product is already selected'),
                    'alcamultifaq_alert_product_max' => $this->l('You cannot select more products for this field'),
                    'alcamultifaqs_lang_select' => $this->l('Select'),
                ]
            );
        }
    }

    /**
     * HOOK Página inicio (Home)
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayHomeCustom($params)
    {
        return $this->setAlcaMultiFaqTemplate('home');
    }

    /**
     * (SÓLO 1.7) HOOK Pié de página general (Footer)
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayFooterBefore($params)
    {
        if ($this->isps17) {
            return $this->setAlcaMultiFaqTemplate('footer');
        }

        return '';
    }

    /**
     * (SÓLO 1.6) HOOK Pié de página general (Footer) o de las diferentes entidades (movidas por JS)
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayFooter($params)
    {
        if (!$this->isps17) {
            if ($this->context->controller->php_self == 'category' ||
                $this->context->controller->php_self == 'cms' ||
                $this->context->controller->php_self == 'product' ||
                $this->context->controller->php_self == 'manufacturer'
            ) {
                return $this->setTemplatesFromNotCustomHook(true) .
                $this->setAlcaMultiFaqTemplate('footer', null, true);
            } else {
                return $this->setAlcaMultiFaqTemplate('footer', null, true);
            }
        }

        return '';
    }

    /**
     * HOOK custom para Página de Categoría
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayAlcaMultiFaqCategory($params)
    {
        if (!empty($params['category']['id']) && $this->isCustomHook('category')) {
            return $this->setAlcaMultiFaqTemplate('category', $params['category']['id']);
        }

        return '';
    }

    /**
     * HOOK custom para Página de Producto
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayAlcaMultiFaqProduct($params)
    {
        if (!empty($params['product']->id) && $this->isCustomHook('product')) {
            return $this->setAlcaMultiFaqTemplate('product', $params['product']->id);
        }

        return '';
    }

    /**
     * HOOK custom para Página de CMS
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayAlcaMultiFaqCMS($params)
    {
        if (!empty($params['cms']['id']) && $this->isCustomHook('cms')) {
            return $this->setAlcaMultiFaqTemplate('cms', $params['cms']['id']);
        }

        return '';
    }

    /**
     * HOOK custom para Página de Fabricante
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayAlcaMultiFaqManufacturer($params)
    {
        if (!empty($params['manufacturer']['id']) && $this->isCustomHook('manufacturer')) {
            return $this->setAlcaMultiFaqTemplate('manufacturer', $params['manufacturer']['id']);
        }

        return '';
    }

    /**
     * (SÓLO 1.7) HOOK general
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayContentWrapperBottom($params)
    {
        if ($this->isps17) {
            return $this->setTemplatesFromNotCustomHook();
        }

        return '';
    }

    /**
     * Estructura de templates para hook hookDisplayContentWrapperBottom / hookDisplayFooter
     *
     * @param array $params
     * @param bool $ps16_move
     *
     * @return string
     */
    public function setTemplatesFromNotCustomHook($ps16_move = false)
    {
        if ($this->context->controller->php_self == 'category' && !$this->isCustomHook('category')) {
            return $this->setAlcaMultiFaqTemplate('category', (int) $this->context->controller->getCategory()->id, $ps16_move);
        }

        if ($this->context->controller->php_self == 'cms' && !$this->isCustomHook('cms')) {
            return $this->setAlcaMultiFaqTemplate('cms', (int) $this->context->controller->cms->id, $ps16_move);
        }

        if ($this->context->controller->php_self == 'product' && !$this->isCustomHook('product')) {
            return $this->setAlcaMultiFaqTemplate('product', (int) $this->context->controller->getProduct()->id, $ps16_move);
        }

        if ($this->context->controller->php_self == 'manufacturer' && !$this->isCustomHook('manufacturer')) {
            if ($id_manufacturer = Tools::getValue('id_manufacturer')) {
                return $this->setAlcaMultiFaqTemplate('manufacturer', (int) $id_manufacturer, $ps16_move);
            }
        }

        return '';
    }

    /**
     * Carga general de hooks para template concreto
     *
     * @param string $type
     * @param int|null $id_object
     * @param bool $ps16_move
     *
     * @return string
     */
    public function setAlcaMultiFaqTemplate($type, $id_object = null, $ps16_move = false)
    {
        $id_lang = $this->context->language->id;

        if (!empty($type)) {
            if ($this->isps17) {
                $templateFile = $this->templateFiles;
            } else {
                $pos = strrpos($this->templateFiles, '/');
                $templateFile = substr($this->templateFiles, $pos + 1);
            }

            $cacheId = $this->name . '|' . $type;

            if (!is_null($id_object) || (int) $id_object != 0) {
                $cacheId = $this->name . '|' . $type . '|' . $id_object;
            }

            if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
                $alcamultifaqs = $this->getDataFaq($type, $id_object);

                $this->context->smarty->assign(
                    [
                        'alcamultifaqs' => $alcamultifaqs,
                        'alcamultifaq_type' => $type,
                        'alcamultifaq_ps16_move' => $ps16_move,
                    ]
                );
            }

            if ($this->isps17) {
                return $this->fetch($this->templateFiles, $this->getCacheId($cacheId));
            } else {
                return $this->display(__FILE__, $templateFile, $this->getCacheId($cacheId));
            }
        }

        return '';
    }

    /**
     * Cargar contenido de FAQ
     *
     * @param string $type
     * @param int|null $id_object
     * @param int $id_shop
     *
     * @return array
     */
    public function getDataFaq($type, $id_object = null, $id_shop = 1)
    {
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        $alcamultifaqs = [];
        $alcamultifaqs = AlcaMultiFaq::getStructureFaq($type, $id_object, $id_lang, $id_shop, true);

        return $alcamultifaqs;
    }

    /**
     * Recibe si debe ir en el hook personalizado o no
     *
     * @param string $type
     *
     * @return bool
     */
    protected function isCustomHook($type)
    {
        return Configuration::get('ALCAMULTIFAQS_CUSTOMHOOK_' . strtoupper($type));
    }
}
