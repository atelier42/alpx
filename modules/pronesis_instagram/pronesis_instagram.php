<?php
/**
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
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'pronesis_instagram/classes/PronesisInstagramAPI.php';

class Pronesis_Instagram extends Module
{
    private $html = '';
    private $postErrors = array();

    public function __construct()
    {
        $this->name = 'pronesis_instagram';
        $this->tab = 'administration';
        $this->version = '1.0.6';
        $this->author = 'Pronesis Srl';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '00418bc19b12513aad0bbd60eaf1af21';

        parent::__construct();

        $this->displayName = $this->l('Instagram feed');
        $this->description = $this->l('Publish images and videos from your Instagram feed.');
    }

    public function hookDisplayInstagramFeed($params)
    {
        if (!$this->active) {
            return;
        }
        if (Configuration::get('INSTAGRAM_ACCESS_TOKEN') && Configuration::get('INSTAGRAM_SHOW_CUSTOM')) {
            $api = new PronesisInstagramAPI($this->context->shop->id);
            $images = $api->getFeed();
            if ($images) {
                $this->context->smarty->assign(
                    array(
                        'images' => $images
                    )
                );
                if (version_compare(_PS_VERSION_, '1.7', '>=') == true) {
                    return $this->context->smarty->fetch($this->local_path.'views/templates/front/feed17.tpl');
                } else {
                    return $this->context->smarty->fetch($this->local_path.'views/templates/front/feed16.tpl');
                }
            }
        }
    }

    public function hookDisplayHome($params)
    {
        if (!$this->active) {
            return;
        }
        if (Configuration::get('INSTAGRAM_ACCESS_TOKEN') && Configuration::get('INSTAGRAM_SHOW_HOME')) {
            $api = new PronesisInstagramAPI($this->context->shop->id);
            $images = $api->getFeed();
            if ($images) {
                $this->context->smarty->assign(
                    array(
                        'images' => $images
                    )
                );
                if (version_compare(_PS_VERSION_, '1.7', '>=') == true) {
                    return $this->context->smarty->fetch($this->local_path.'views/templates/front/feed17.tpl');
                } else {
                    return $this->context->smarty->fetch($this->local_path.'views/templates/front/feed16.tpl');
                }
            }
        }
    }

    public function hookDisplayHeader($params)
    {
        if (!$this->active) {
            return;
        }
        if (Configuration::get('INSTAGRAM_SHOW_HOME') || Configuration::get('INSTAGRAM_SHOW_CUSTOM')) {
            if (version_compare(_PS_VERSION_, '1.7', '<') == true) {
                $this->context->controller->addJS('modules/' . $this->name . '/views/js/jquery.slick.min.js');
                $this->context->controller->addJS('modules/' . $this->name . '/views/js/instagram-1.0.2.js');
                $this->context->controller->addCSS('modules/' . $this->name . '/views/css/jquery.slick.min.css');
                $this->context->controller->addCSS('modules/' . $this->name . '/views/css/instagram-1.0.2.css');
            }
        }
    }

    /**
     * Hook needed by version 1.7 to load css and js
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        if (!$this->active) {
            return;
        }
        if (Configuration::get('INSTAGRAM_SHOW_HOME') || Configuration::get('INSTAGRAM_SHOW_CUSTOM')) {
            if (version_compare(_PS_VERSION_, '1.7', '>=') == true) {
                $this->context->controller->addJqueryPlugin('fancybox');
                $this->context->controller->registerJavascript('instagram-slick-js', 'modules/' . $this->name . '/views/js/jquery.slick.min.js');
                $this->context->controller->registerJavascript('instagram-js', 'modules/' . $this->name . '/views/js/instagram-1.0.2.js');
                $this->context->controller->registerStylesheet('instagram-slick-css', 'modules/' . $this->name . '/views/css/jquery.slick.min.css');
                $this->context->controller->registerStylesheet('instagram-css', 'modules/' . $this->name . '/views/css/instagram-1.0.2.css');
            }
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        /* set default values */
        if (!Configuration::get('INSTAGRAM_ACCESS_TOKEN')) {
            Configuration::updateValue('INSTAGRAM_ACCESS_TOKEN', '');
        }
        if (!Configuration::get('INSTAGRAM_SHOW_HOME')) {
            Configuration::updateValue('INSTAGRAM_SHOW_HOME', 1);
        }
        if (!Configuration::get('INSTAGRAM_SHOW_CUSTOM')) {
            Configuration::updateValue('INSTAGRAM_SHOW_CUSTOM', 1);
        }
        if (!Configuration::get('INSTAGRAM_MAX_ITEMS')) {
            Configuration::updateValue('INSTAGRAM_MAX_ITEMS', 5);
        }
        if (!Configuration::get('INSTAGRAM_CACHE_LIFE')) {
            Configuration::updateValue('INSTAGRAM_CACHE_LIFE', 1);
        }
        if (!Configuration::get('INSTAGRAM_DEBUG')) {
            Configuration::updateValue('INSTAGRAM_DEBUG', 0);
        }
        if (!Configuration::get('INSTAGRAM_ACCESS_TOKEN_EXPIRATION')) {
            Configuration::updateValue('INSTAGRAM_ACCESS_TOKEN_EXPIRATION', 0);
        }
        if (!Configuration::get('INSTAGRAM_CACHE_LAST_UPDATE')) {
            Configuration::updateValue('INSTAGRAM_CACHE_LAST_UPDATE', 0);
        }
        if (parent::install()) {
            if (version_compare(_PS_VERSION_, '1.7', '>=') == true) {
                return $this->registerHook('displayInstagramFeed') &&
                $this->registerHook('actionFrontControllerSetMedia') &&
                $this->registerHook('displayHome');
            } else {
                return $this->registerHook('displayInstagramFeed') &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayHome');
            }
        }
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        $request_uri = $this->context->link->getAdminLink('AdminModules', false).
                       '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.
                       $this->name.'&token='.Tools::getAdminTokenLite('AdminModules');
        $current_view = Tools::getValue('current_view');
        $ps_url = _MODULE_DIR_.'pronesis_instagram/';
        $this->html = '';
        if (Tools::isSubmit('submitMain')) {
            $this->postValidation();
            if (!count($this->postErrors)) {
                $this->postProcess();
            } else {
                foreach ($this->postErrors as $err) {
                    $this->html .= $this->displayError($err);
                }
            }
        }
        // check if webservice is responding
        $api = new PronesisInstagramAPI($this->context->shop->id);
        if (!$api->is_connect) {
            $this->html .= $this->displayError($this->l('The access token is not working or missing!'));
        }
        // force refresh cache
        if (Tools::getIsset('force_refresh')) {
            if ($api->is_connect) {
                $api->refreshCache(true);
            }
        }
        if (!$logs = Tools::file_get_contents(dirname(__FILE__) . '/logs/instagram.log')) {
            $logs = $this->l('Logs not found.');
        }
        $this->context->smarty->assign(
            array(
                'ps_url' => $ps_url,
                'current_view' => $current_view,
                'module_display' => $this->displayName,
                'request_uri' => $request_uri,
                'module_version' => $this->version,
                'config_form' => $this->renderForm(),
                'images' => $api->getFeed(),
                'logs' => $logs,
            )
        );
        return $this->html . $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
    }

    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMain';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.
                                $this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        $items = array();
        for ($i=2; $i <= 50; $i++) {
            $items[] = array('id' => $i, 'name' => $i . ' ' . $this->l('items'));
        }
        $form = array(
            'form' => array(
                'input' => array(
                    array(
                        'type' => 'text',
                        'required' => true,
                        'desc' => $this->l('Access Token, please check help on how to get an access token'),
                        'name' => 'INSTAGRAM_ACCESS_TOKEN',
                        'label' => $this->l('Access Token'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Max items'),
                        'desc' => $this->l('Max items to be shown'),
                        'name' => 'INSTAGRAM_MAX_ITEMS',
                        'required' => true,
                        'options' => array(
                            'query' => $items,
                            'id' => 'id',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Cache life'),
                        'name' => 'INSTAGRAM_CACHE_LIFE',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                            array(
                                                'id' => 1,
                                                'name' => '1 ' . $this->l('hour'),
                                            ),
                                            array(
                                                'id' => 4,
                                                'name' => '4 ' . $this->l('hours'),
                                            ),
                                            array(
                                                'id' => 24,
                                                'name' => '1 ' . $this->l('day'),
                                            )

                                       ),
                            'id' => 'id',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show in home page'),
                        'name' => 'INSTAGRAM_SHOW_HOME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show in custom position'),
                        'name' => 'INSTAGRAM_SHOW_CUSTOM',
                        'desc' => $this->l('Place in any position using this code in a template file (tpl): ') .
                                  "{hook h='displayInstagramFeed' mod='pronesis_instagram'}",
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                            )
                        ),
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Debug module'),
                        'name' => 'INSTAGRAM_DEBUG',
                        'is_bool' => true,
                        'desc' => $this->l('Enable to get more logging infos'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
        return $form;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $configs = array(
            'INSTAGRAM_ACCESS_TOKEN' => Tools::getValue('INSTAGRAM_ACCESS_TOKEN', Configuration::get('INSTAGRAM_ACCESS_TOKEN')),
            'INSTAGRAM_MAX_ITEMS' => Tools::getValue('INSTAGRAM_MAX_ITEMS', Configuration::get('INSTAGRAM_MAX_ITEMS')),
            'INSTAGRAM_CACHE_LIFE' => Tools::getValue('INSTAGRAM_CACHE_LIFE', Configuration::get('INSTAGRAM_CACHE_LIFE')),
            'INSTAGRAM_SHOW_HOME' => Tools::getValue('INSTAGRAM_SHOW_HOME', Configuration::get('INSTAGRAM_SHOW_HOME')),
            'INSTAGRAM_DEBUG' => Tools::getValue('INSTAGRAM_DEBUG', Configuration::get('INSTAGRAM_DEBUG')),
            'INSTAGRAM_SHOW_CUSTOM' => Tools::getValue('INSTAGRAM_SHOW_CUSTOM', Configuration::get('INSTAGRAM_SHOW_CUSTOM')),
        );
        return $configs;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        if (Tools::isSubmit('submitMain')) {
            $form_values = $this->getConfigFormValues();
            foreach (array_keys($form_values) as $key) {
                if (strpos($key, '[]') !== false) {
                    if (!is_array(Tools::getValue($key))) {
                        $key = str_replace('[]', '', $key);
                        Configuration::updateValue($key, implode(',', Tools::getValue($key)));
                    }
                } else {
                    // specific case for access token, if it's different from the saved one,
                    // force refresh to have a valid expiration date
                    if ($key == 'INSTAGRAM_ACCESS_TOKEN') {
                        if (Configuration::get('INSTAGRAM_ACCESS_TOKEN') != Tools::getValue($key)) {
                            // the user changed token, we need to refresh
                            Configuration::updateValue($key, Tools::getValue($key));
                            $api = new PronesisInstagramAPI($this->context->shop->id);
                            $api->refreshToken(true);
                        }
                    } else {
                        Configuration::updateValue($key, Tools::getValue($key));
                    }
                }
            }
            $this->html .= $this->displayConfirmation($this->l('Settings updated'));
        }
    }

    private function postValidation()
    {
        return true;
    }
}
