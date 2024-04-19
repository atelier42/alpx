<?php
/**
 * Related Products
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *  @author    FME Modules
 *  @copyright 2021 fmemodules All right reserved
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  @category  FMM Modules
 *  @package   Related Products
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
 
include_once dirname(__FILE__) . '/model/RelatedproductsModel.php';
include_once dirname(__FILE__) . '/model/RelatedProductsRules.php';
include_once dirname(__FILE__) . '/model/RelatedProductVisibility.php';

class RelatedProducts extends Module
{
    private $tab_class = 'RelatedProducts';
    public function __construct()
    {
        $this->name = 'relatedproducts';
        $this->tab = 'front_office_features';
        $this->version = '2.2.2';
        $this->author = 'FMM Modules';
        $this->module_key = '490fe1eadf63c243df86494540d4f70f';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';

        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Related Products');
        $this->description = $this->l(
            'Allows to add and remove related products with each product.'
        );
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('RELATEDPRODUCTS_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_IMAGE_TYPES',
                ImageType::getFormattedName('home')
            );
        }
        return parent::install()
            && $this->addTab($this->tab_class, 0)
            && $this->registerHook('header')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('displayProductTabContent')
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('productfooter')
            && $this->registerHook('displayFmmRelatedProducts')
            && $this->registerHook('displayHome')
            && $this->registerHook('displayShoppingCartFooter')
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_PRODUCT', 1)
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_HOME', 0)
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_CAT', 0)
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_CMS', 0)
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_CART', 1)
            && Configuration::updateValue('RELATED_PRODUCTSBLOCK_VIEW', 'grid')
            && Configuration::updateValue(
                'RELATED_PRODUCTS_SHOP',
                $this->context->shop->id
            )
            && Configuration::updateValue('RELATED_PRODUCT_IMAGE', '1');
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';
        return (parent::uninstall()
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_PRODUCT')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_HOME')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_CAT')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_CMS')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_CART')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_VIEW')
            && Configuration::deleteByName('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
            && Configuration::deleteByName('RELATED_PRODUCTS_SHOP')
            && $this->removeTab());
    }

    public function addTab($tab_class, $id_parent)
    {
        //** @function to add tab in admin backend
        $tab = new Tab();
        $tab->id_parent = $id_parent;
        $tab->module = $this->name;
        $tab->class_name = $tab_class;
        $tab->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->displayName;
        if (true === Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $tab->icon = 'card_giftcard';
        }
        $tab->add();

        $subtab1 = new Tab();
        $subtab1->id_parent = Tab::getIdFromClassName($tab_class);
        $subtab1->module = $this->name;
        $subtab1->class_name = 'AdminRelatedProductsRules';
        $subtab1->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l(
            'Related Products Rules'
        );
        $subtab1->add();

        $subtab2 = new Tab();
        $subtab2->id_parent = Tab::getIdFromClassName($tab_class);
        $subtab2->module = $this->name;
        $subtab2->class_name = 'AdminRelatedProductsVisibility';
        $subtab2->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l(
            'Related Products Visibility Criteria'
        );
        $subtab2->add();

        return true;
    }

    private function removeTab()
    {
        $res = true;
        $id_tab1 = Tab::getIdFromClassName('RelatedProducts');
        if ($id_tab1 != 0) {
            $tab = new Tab($id_tab1);
            $res &= $tab->delete();
        }
        $id_tab1 = Tab::getIdFromClassName('AdminRelatedProductsRules');
        if ($id_tab1 != 0) {
            $tab = new Tab($id_tab1);
            $res &= $tab->delete();
        }
        $id_tab2 = Tab::getIdFromClassName('AdminRelatedProductsVisibility');
        if ($id_tab2 != 0) {
            $tab = new Tab($id_tab2);
            $res &= $tab->delete();
        }

        return $res;
    }

    public function hookDisplayHeader()
    {
        if (Configuration::get('RELATED_PRODUCTSBLOCK_VIEW') == 'grid') {
            $this->context->controller->addCSS($this->_path .
                'views/css/owl.carousel.css', 'all');
            $this->context->controller->addJS($this->_path .
                'views/js/owl.carousel.js');
        }
        $this->context->controller->addJS($this->_path .
            'views/js/related_products.js');
        $this->context->controller->addCSS($this->_path .
            'views/css/relatedproducts_block_' . Configuration::get(
                'RELATED_PRODUCTSBLOCK_VIEW'
            ) . '.css', 'all');

        $this->context->smarty->assign('base_dir', _PS_BASE_URL_ . __PS_BASE_URI__);
        $this->context->smarty->assign('static_token', Tools::getToken(false));
        $this->context->smarty->assign(
            'js_path',
            $this->_path . 'views/js/related_products.js'
        );
        $this->context->smarty->assign(
            'cart_link',
            $this->context->link->getPageLink('cart')
        );
        $this->context->smarty->assign(
            'current_page',
            Dispatcher::getInstance()->getController()
        );
        $this->context->smarty->assign(
            'rp_view',
            Configuration::get('RELATED_PRODUCTSBLOCK_VIEW')
        );
        $this->context->smarty->assign(
            'rp_image_type',
            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
        );
        $this->context->smarty->assign(
            'ps_version',
            (int) (Tools::version_compare(_PS_VERSION_, '1.7', '>=') ? 1 : 0)
        );
        $this->context->smarty->assign(
            'currency_sign',
            isset(
                $this->context->currency->sign
            ) ? $this->context->currency->sign : $this->context->currency->iso_code
        );
        return $this->display($this->_path, 'views/templates/hook/header.tpl');
    }

    public function hookDisplayProductTab()
    {
        $id_current = Tools::getValue('id_product');
        $products_list = array();
        $products_list = RelatedproductsModel::selectRow($id_current);
        $this->context->smarty->assign(
            'my_module_name',
            Configuration::get('BACKGROUNDSWITCHER_NAME')
        );
        if ($products_list) {
            return $this->display(__FILE__, 'relatedproductstab.tpl');
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $id_rel = Tools::getValue('id_product');
        $module_link = $this->context->link->getAdminLink('AdminModules', false);
        $token = Tools::getAdminTokenLite('AdminModules');
        $url = $module_link . '&configure=' . $this->name . '&token=' . $token .
        '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $this->context->smarty->assign('url', $url);
        $id_lang = $this->context->cookie->id_lang;
        $id_current = Tools::getValue('id_product');
        $id_current = ($id_current <= 0) ? (int) $params['id_product'] : $id_current;
        $products_list = array();
        $products_list = RelatedproductsModel::selectRow($id_current);
        $this->context->smarty->assign('id_rel', $id_rel);
        $this->context->smarty->assign('products_list', $products_list);
        $this->context->smarty->assign('ps_version', _PS_VERSION_);
        $this->context->smarty->assign('id_current', $id_current);

        $force_ssl = (Configuration::get('PS_SSL_ENABLED') &&
            Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $this->context->smarty->assign(array(
            'id_lang' => $id_lang,
            'link' => $this->context->link,
            'search_ssl' => Tools::usingSecureMode(),
            'base_admin_url' => (($force_ssl) ? _PS_BASE_URL_SSL_ .
                __PS_BASE_URI__ : _PS_BASE_URL_ . __PS_BASE_URI__) .
            basename(_PS_ADMIN_DIR_),
            'action_url' => $url .
            '&action=getSearchProducts&forceJson=1&disableCombination=0&exclude_packs=0&excludeVirtuals=0&limit=20',
        ));
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
            return $this->display(
                $this->_path,
                'views/templates/admin/AdminRelatedProducts_17.tpl'
            );
        } else {
            return $this->display(
                $this->_path,
                'views/templates/admin/AdminRelatedProducts.tpl'
            );
        }
    }

    public function getContent()
    {
        $this->html = $this->display(__FILE__, 'views/templates/hook/info.tpl');
        $this->postProcess();
        if (Tools::getValue('configure') == 'relatedproducts') {
            $this->context->controller->informations[] = $this->l(
                'New Settings Tab for Related products is created in
                Catalog => Products => Edit any Product'
            );
            $this->context->controller->warnings[] = $this->l(
                'For category and CMS pages Plesae use this hook in Your template. {hook h="displayFmmRelatedProducts"}'
            );
            return $this->html . $this->configForm();
        }
    }

    protected function postProcess()
    {
        $action = Tools::getValue('action');
        if ($action == 'getSearchProducts') {
            RelatedProductsRules::getSearchProducts();
            die();
        } elseif ($action == 'allcombinations') {
            $pid = (int) Tools::getValue('id_related');
            $product = new Product((int) $pid, true, (int) $this->context->language->id);
            $i = 0;
            $html = '';
            $mycombinations = array();
            if ($product->id) {
                $attributes = $product->getAttributesGroups(
                    (int) $this->context->language->id
                );
                $combinations = array();
                if (!empty($attributes)) {
                    foreach ($attributes as $attribute) {
                        $combinations[$attribute['id_product_attribute']]['id_product_attribute'] = $attribute[
                            'id_product_attribute'
                        ];
                        if (isset($combinations[$attribute['id_product_attribute']]['attributes'])) {
                            $combinations[$attribute['id_product_attribute']]['attributes'] .= $attribute[
                                'attribute_name'
                            ] . ' - ';
                        } else {
                            $combinations[$attribute['id_product_attribute']]['attributes'] = '';
                        }

                        $combinations[$attribute['id_product_attribute']]['price'] = Tools::displayPrice(
                            Tools::convertPrice(
                                Product::getPriceStatic(
                                    (int) $product->id,
                                    true,
                                    $attribute['id_product_attribute']
                                ),
                                $this->context->currency
                            ),
                            $this->context->currency
                        );
                    }

                    foreach ($combinations as $combination) {
                        $mycombinations[$i]['attributes'] = rtrim($combination['attributes'], ' - ');
                        $mycombinations[$i]['id_product_attribute'] = $combination[
                            'id_product_attribute'
                        ];
                        $i++;
                    }

                    foreach ($mycombinations as $combination) {
                        if (isset($combination)) {
                            $html .= '<option value="' .
                                $combination['id_product_attribute'] . '">' .
                                $combination['attributes'] . '</option>';
                        }
                    }
                } else {
                    $html .= '<option value="0">' .
                    $this->l('Simple product - No combinations') . '</option>';
                }
            }
            echo $html;
            die();
        } elseif ($action == 'deleteRelatedproduct') {
            $id_current = (int) Tools::getValue('id_current');
            $id_related = (int) Tools::getvalue('product_id');
            $id_comb = (int) Tools::getvalue('id_comb');

            $result = array('success' => false, 'msg' => $this->l(
                'Operation failed. Cannot delete related product.'
            ));
            if (RelatedproductsModel::deleteRelatedRow(
                $id_current,
                $id_related,
                $id_comb
            )) {
                $result = array(
                    'success' => true,
                    'msg' => $this->l('Related product deleted successfully.'),
                );
            }
            die(json_encode($result));
        }

        if (Tools::isSubmit('submitRelatedSettings')) {
            $shops = (Tools::getValue(
                'checkBoxShopAsso_related_products'
            )) ? implode(
                ',',
                Tools::getValue(
                    'checkBoxShopAsso_related_products'
                )
            ) : $this->context->shop->id;
            Configuration::updateValue('RELATED_PRODUCTS_SHOP', $shops);
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_CMS',
                (int) Tools::getValue('RELATED_PRODUCTSBLOCK_CMS')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_CAT',
                (int) Tools::getValue('RELATED_PRODUCTSBLOCK_CAT')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_HOME',
                (int) Tools::getValue('RELATED_PRODUCTSBLOCK_HOME')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_PRODUCT',
                (int) Tools::getValue('RELATED_PRODUCTSBLOCK_PRODUCT')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_CART',
                (int) Tools::getValue('RELATED_PRODUCTSBLOCK_CART')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_VIEW',
                Tools::getValue('RELATED_PRODUCTSBLOCK_VIEW')
            );
            Configuration::updateValue(
                'RELATED_PRODUCTSBLOCK_IMAGE_TYPES',
                Tools::getValue('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
            );
            Configuration::updateValue(
                'RELATED_PRODUCT_IMAGE',
                Tools::getValue('RELATED_PRODUCT_IMAGE')
            );
            return $this->context->controller->confirmations[] = $this->l(
                'Updated Successfully'
            );
        }
    }

    protected function configForm()
    {
        $type = (Tools::version_compare(
            _PS_VERSION_,
            '1.6.0.0',
            '<'
        )) ? 'radio' : 'switch';
        $img_types = RelatedProductVisibility::getImageTypes();
        $types_list = array();
        foreach ($img_types as $key => $img) {
            /* @var Image Types $img */
            $types_list[$key]['id'] = $img['id_image_type'];
            $types_list[$key]['id_option'] = $img['name'];
            $types_list[$key]['name'] = $img['name'];
        }
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => $type,
                        'label' => $this->l(
                            'Display Related Products on Home Page'
                        ),
                        'name' => 'RELATED_PRODUCTSBLOCK_HOME',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products block on HOME page.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_HOME_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_HOME_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $type,
                        'label' => $this->l(
                            'Display Related Products on Category Page'
                        ),
                        'name' => 'RELATED_PRODUCTSBLOCK_CAT',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products block on Category page.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CAT_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CAT_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $type,
                        'label' => $this->l(
                            'Display Related Products on Product Page'
                        ),
                        'name' => 'RELATED_PRODUCTSBLOCK_PRODUCT',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products block on product page.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_PRODUCT_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_PRODUCT_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $type,
                        'label' => $this->l('Display Related Products on Cart Page'),
                        'name' => 'RELATED_PRODUCTSBLOCK_CART',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products block on cart page.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CART_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CART_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $type,
                        'label' => $this->l(
                            'Display Related Products on CMS Page'
                        ),
                        'name' => 'RELATED_PRODUCTSBLOCK_CMS',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products block on CMS page.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CMS_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCTSBLOCK_CMS_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $type,
                        'label' => $this->l('Display image thumbnail'),
                        'name' => 'RELATED_PRODUCT_IMAGE',
                        'class' => 't',
                        'desc' => $this->l(
                            'Enable/Disable related products image thumbnail.'
                        ),
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'RELATED_PRODUCT_image_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'RELATED_PRODUCT_image_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Related Products View'),
                        'name' => 'RELATED_PRODUCTSBLOCK_VIEW',
                        'options' => array(
                            'id' => 'id_option',
                            'name' => 'name',
                            'query' => array(
                                array(
                                    'id_option' => 'list',
                                    'name' => $this->l('List View'),
                                ),
                                array(
                                    'id_option' => 'grid',
                                    'name' => $this->l('Grid View'),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Related Products View'),
                        'name' => 'RELATED_PRODUCTSBLOCK_IMAGE_TYPES',
                        'options' => array(
                            'id' => 'id_option',
                            'name' => 'name',
                            'query' => $types_list,
                        ),
                    ),
                ),
                'submit' => array('title' => $this->l('Save')),
            ),
        );

        if (Shop::isFeatureActive()) {
            $fields_form['form']['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                'col' => '6',
            );
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = 'related_products';
        $helper->module = $this;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get(
            'PS_BO_ALLOW_EMPLOYEE_FORM_LANG'
        ) ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRelatedSettings';
        $helper->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ) . '&configure=' . $this->name . '&tab_module=' .
        $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $selected_shops = (Configuration::get(
            'RELATED_PRODUCTS_SHOP'
        )) ? Configuration::get('RELATED_PRODUCTS_SHOP') : '';
        $this->context->smarty->assign('selected_shops', $selected_shops);
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        $html = $this->context->smarty->fetch(dirname(__FILE__) .
            '/views/templates/admin/config.tpl');
        return $helper->generateForm(array($fields_form)) . $html;
    }

    protected function getConfigFieldsValues()
    {
        return array(
            'RELATED_PRODUCTSBLOCK_CMS' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_CMS'
            ),            'RELATED_PRODUCTSBLOCK_CAT' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_CAT'
            ),            'RELATED_PRODUCTSBLOCK_HOME' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_HOME'
            ),
            'RELATED_PRODUCTSBLOCK_CART' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_CART'
            ),
            'RELATED_PRODUCTSBLOCK_PRODUCT' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_PRODUCT'
            ),
            'RELATED_PRODUCTSBLOCK_VIEW' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_VIEW'
            ),
            'RELATED_PRODUCT_IMAGE' => Configuration::get(
                'RELATED_PRODUCT_IMAGE'
            ),
            'RELATED_PRODUCTSBLOCK_IMAGE_TYPES' => Configuration::get(
                'RELATED_PRODUCTSBLOCK_IMAGE_TYPES'
            ),
        );
    }

    public function hookActionProductUpdate()
    {
        $id_current = Tools::getValue('id_product');
        $relatedProducts = Tools::getValue('related_products');
        if (isset($relatedProducts) && $relatedProducts) {
            foreach ($relatedProducts as $product) {
                if (false === RelatedproductsModel::existRelatedProduct(
                    $id_current,
                    key($product),
                    current($product)
                )) {
                    $relatedProduct = new RelatedproductsModel();
                    $relatedProduct->id_current = (int) $id_current;
                    $relatedProduct->id_related = key($product);
                    $relatedProduct->id_combination = current($product);
                    $relatedProduct->save();
                }
            }
        }
    }

    public function hookDisplayProductTabContent()
    {
        $id_product = Tools::getValue('id_product');
        if (Configuration::get('RELATED_PRODUCTSBLOCK_PRODUCT') == 1) {
            return $this->displayRelatedProducts($id_product);
        }
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') == 'AdminProducts' &&
            Tools::version_compare(_PS_VERSION_, '1.7', '<') == true) {
            $this->context->controller->addJqueryPlugin('autocomplete');
        }
    }

    public function hookProductFooter()
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) {
            $id_product = Tools::getValue('id_product');
            if (Configuration::get('RELATED_PRODUCTSBLOCK_PRODUCT') == 1) {
                return $this->displayRelatedProducts($id_product);
            }
        }
    }

    public function hookDisplayHome()
    {
        return $this->hookDisplayFmmRelatedProducts();
    }

    /**
     * Custom Hook For CMS, CATEGORY, and PRODUCT page
     */

    public function hookDisplayFmmRelatedProducts()
    {
        $cont = $this->getCurrentCont();
        $cms = Configuration::get('RELATED_PRODUCTSBLOCK_CMS');
        $home = Configuration::get('RELATED_PRODUCTSBLOCK_HOME');
        $category = Configuration::get('RELATED_PRODUCTSBLOCK_CAT');
        

        if ($cont == 'cms' && $cms == 0) {
            return;
        } elseif ($cont == 'category' && $category == 0) {
            return;
        } elseif ($cont == 'home' && $home == 0) {
            return;
        } else {
            return $this->displayRelatedProductsNew($cont);
        }
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        if (Configuration::get('RELATED_PRODUCTSBLOCK_CART') == 1) {
            if (isset($params['cart']) && $params['cart']) {
                $cartPoducts = RelatedproductsModel::getCartRelatedProducts(
                    $params['cart']->id
                );
                if (isset($cartPoducts) && $cartPoducts) {
                    $id_random_related = $cartPoducts[array_rand($cartPoducts)];
                    if ($id_random_related) {
                        return $this->displayRelatedProducts(
                            $id_random_related,
                            'cart'
                        );
                    }
                } else {
                    $getcartProducts = RelatedproductsModel::getCartsProducts(
                        $params['cart']->id
                    );
                    if (isset($getcartProducts) && $getcartProducts) {
                        $id_random_related = $getcartProducts[array_rand(
                            $getcartProducts
                        )];
                        if ($id_random_related) {
                            return $this->displayRelatedProducts(
                                $id_random_related,
                                'cart'
                            );
                        }
                    }
                }
            }
        }
    }

    protected function displayRelatedProductsNew($cont)
    {
        $rules = RelatedProductsRules::getAllRules();
        $pro_array = array();
        if (!empty($rules)) {
            foreach ($rules as $keys => $rule) {
                if ($cont == 'index' && $rule['id_page'] != 1) {
                    continue;
                } elseif ($cont == 'category' && $rule['id_page'] != 2) {
                    continue;
                } elseif ($cont == 'cms' && $rule['id_page'] != 5) {
                    continue;
                }

                if ($cont == 'cms') {
                    $cms_page_id = Tools::getValue('id_cms');
                    if ($rule['id_page'] == 5 && in_array($cms_page_id, explode(',', $rule['id_cms_pages']))) {
                    } else {
                        continue;
                    }
                }

                if ((int) $rule['id_rules'] == 1) {
                    $bestsales = ProductSale::getBestSales(
                        $this->context->language->id,
                        null,
                        (int) $rule['no_products']
                    );
                    $products_list_bestsales = array();
                    if (isset($bestsales) && $bestsales) {
                        foreach ($bestsales as $key => $value) {
                            $products_list_bestsales[$key]['id_related'] = $value['id_product'];
                            $products_list_bestsales[$key]['id_combination'] = $value['id_product_attribute'];
                            $products_list_bestsales[$key]['id_rules'] = $rule['id_rules'];
                        }
                    }
                    $products_list = array();
                    $products_list = $products_list_bestsales;
                    if (isset($products_list) && !empty($products_list)) {
                        foreach ($products_list as &$rel_product) {
                            $temp_product = new Product(
                                (int) $rel_product['id_related'],
                                true,
                                (int) $this->context->language->id
                            );
                            $cover = Product::getCover($rel_product['id_related']);
                            $rel_product['id_image'] = (int) $cover['id_image'];
                            $rel_product['attributes'] = array();
                            if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                $this->context->language->id
                            )) {
                                $rel_product['attributes'] = Product::getAttributesParams(
                                    (int) $rel_product['id_related'],
                                    $rel_product['id_combination']
                                );
                                if (array_key_exists($rel_product['id_combination'], $images)) {
                                    foreach ($images[$rel_product['id_combination']] as $image) {
                                        if (isset($image) && $image) {
                                            $rel_product['id_image'] = $image['id_image'];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $cover_image = Product::getCover($temp_product->id);
                                if ($cover_image) {
                                    $rel_product['id_image'] = $cover_image['id_image'];
                                }
                            }
                            $temp_product->price = Product::getPriceStatic(
                                $rel_product['id_related'],
                                true,
                                $rel_product['id_combination']
                            );
                            $rel_product['products'] = $temp_product;
                        }
                        $ids = array_column($products_list, 'id_related');
                        $ids = array_unique($ids);
                        $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                            $key = $key;
                            return in_array($value, array_keys($ids));
                        }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                        $position = (true === Tools::version_compare(
                            _PS_VERSION_,
                            '1.7',
                            '>='
                        )) ? ' ps_new' : '';
                        $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                        $this->context->smarty->assign('display_image', $display_image);
                        $this->context->smarty->assign('products_list', $products_list);
                        $this->context->smarty->assign('class_name', $position);
                        $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                        $this->context->smarty->assign(
                            'img_type',
                            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                        );
                        $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                        if ((Shop::isFeatureActive() && !empty($shops) && in_array(
                            $this->context->shop->id,
                            explode(',', $shops)
                        )) || !Shop::isFeatureActive()) {
                            $pro_array[$keys]['rules'] = $rules;
                            $pro_array[$keys]['rules_title'] = $rule['titles'];
                            $pro_array[$keys]['display_image'] = $display_image;
                            $pro_array[$keys]['products_list'] = $products_list;
                            $pro_array[$keys]['class_name'] = $position;
                            $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                        }
                    }
                } elseif ($rule['id_rules'] == 2) {
                    $dropPrice = Product::getPricesDrop(
                        $this->context->language->id,
                        null,
                        (int) $rule['no_products']
                    );

                    $products_list_droppricepro = array();
                    if (isset($dropPrice) && $dropPrice) {
                        foreach ($dropPrice as $key => $value) {
                            $products_list_droppricepro[$key]['id_related'] = $value[
                                    'id_product'
                                ];
                            $products_list_droppricepro[$key]['id_combination'] = $value[
                                    'id_product_attribute'
                                ];
                        }
                    }
                    $products_list = $products_list_droppricepro;
                    
                    if (isset($products_list) && $products_list) {
                        foreach ($products_list as &$rel_product) {
                            $temp_product = new Product(
                                (int) $rel_product['id_related'],
                                true,
                                (int) $this->context->language->id
                            );
                            $cover = Product::getCover($rel_product['id_related']);
                            $rel_product['id_image'] = (int) $cover['id_image'];
                            $rel_product['attributes'] = array();
                            if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                $this->context->language->id
                            )) {
                                $rel_product['attributes'] = Product::getAttributesParams(
                                    (int) $rel_product['id_related'],
                                    $rel_product['id_combination']
                                );
                                if (array_key_exists($rel_product['id_combination'], $images)) {
                                    foreach ($images[$rel_product['id_combination']] as $image) {
                                        if (isset($image) && $image) {
                                            $rel_product['id_image'] = $image['id_image'];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $cover_image = Product::getCover($temp_product->id);
                                if ($cover_image) {
                                    $rel_product['id_image'] = $cover_image['id_image'];
                                }
                            }
                            $temp_product->price = Product::getPriceStatic(
                                $rel_product['id_related'],
                                true,
                                $rel_product['id_combination']
                            );
                            $rel_product['products'] = $temp_product;
                        }
                        $ids = array_column($products_list, 'id_related');
                        $ids = array_unique($ids);
                        $products_list = array_filter(
                            $products_list,
                            function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            },
                            ARRAY_FILTER_USE_BOTH
                        ); //for removing replicate data
                        $position = (true === Tools::version_compare(
                            _PS_VERSION_,
                            '1.7',
                            '>='
                        )) ? ' ps_new' : '';
                        $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                        $this->context->smarty->assign('display_image', $display_image);
                        $this->context->smarty->assign('products_list', $products_list);
                        $this->context->smarty->assign('class_name', $position);
                        $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                        $this->context->smarty->assign(
                            'img_type',
                            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                        );
                        $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                        if ((Shop::isFeatureActive() && !empty($shops) &&
                            in_array(
                                $this->context->shop->id,
                                explode(',', $shops)
                            )) || !Shop::isFeatureActive()) {
                            $pro_array[$keys]['rules'] = $rules;
                            $pro_array[$keys]['rules_title'] = $rule['titles'];
                            $pro_array[$keys]['display_image'] = $display_image;
                            $pro_array[$keys]['products_list'] = $products_list;
                            $pro_array[$keys]['class_name'] = $position;
                            $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                        }
                    }
                } elseif ((int) $rule['id_rules'] == 3) {
                    $newpro = Product::getNewProducts(
                        $this->context->language->id,
                        null,
                        (int) $rule['no_products']
                    );
                    $products_list_newpro = array();
                    if (isset($newpro) && $newpro) {
                        foreach ($newpro as $key => $value) {
                            $products_list_newpro[$key]['id_related'] = $value[
                                'id_product'
                            ];
                            $products_list_newpro[$key]['id_combination'] = $value[
                                'id_product_attribute'
                            ];
                        }
                        $products_list = $products_list_newpro;
            
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter(
                                $products_list,
                                function ($key, $value) use ($ids) {
                                    $key = $key;
                                    return in_array($value, array_keys($ids));
                                },
                                ARRAY_FILTER_USE_BOTH
                            ); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                            in_array(
                                $this->context->shop->id,
                                explode(',', $shops)
                            )) || !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    }
                } elseif ((int) $rule['id_rules'] == 4) {
                    $products_list_topViewed = array();
                    $products_list_topViewed = RelatedProductsRules::getTopViewedProducts(
                        $rule['no_products']
                    );
                    
                    $products_list = $products_list_topViewed;
        
                    if (isset($products_list) && $products_list) {
                        foreach ($products_list as &$rel_product) {
                            $temp_product = new Product(
                                (int) $rel_product['id_related'],
                                true,
                                (int) $this->context->language->id
                            );
                            $cover = Product::getCover($rel_product['id_related']);
                            $rel_product['id_image'] = (int) $cover['id_image'];
                            $rel_product['attributes'] = array();
                            if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                $this->context->language->id
                            )) {
                                $rel_product['attributes'] = Product::getAttributesParams(
                                    (int) $rel_product['id_related'],
                                    $rel_product['id_combination']
                                );
                                if (array_key_exists($rel_product['id_combination'], $images)) {
                                    foreach ($images[$rel_product['id_combination']] as $image) {
                                        if (isset($image) && $image) {
                                            $rel_product['id_image'] = $image['id_image'];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $cover_image = Product::getCover($temp_product->id);
                                if ($cover_image) {
                                    $rel_product['id_image'] = $cover_image['id_image'];
                                }
                            }
                            $temp_product->price = Product::getPriceStatic(
                                $rel_product['id_related'],
                                true,
                                $rel_product['id_combination']
                            );
                            $rel_product['products'] = $temp_product;
                        }
                        $ids = array_column($products_list, 'id_related');
                        $ids = array_unique($ids);
                        $products_list = array_filter(
                            $products_list,
                            function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            },
                            ARRAY_FILTER_USE_BOTH
                        ); //for removing replicate data
                        $position = (true === Tools::version_compare(
                            _PS_VERSION_,
                            '1.7',
                            '>='
                        )) ? ' ps_new' : '';
                        $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                        $this->context->smarty->assign('display_image', $display_image);
                        $this->context->smarty->assign('products_list', $products_list);
                        $this->context->smarty->assign('class_name', $position);
                        $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                        $this->context->smarty->assign(
                            'img_type',
                            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                        );
                        $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                        if ((Shop::isFeatureActive() && !empty($shops) &&
                            in_array($this->context->shop->id, explode(',', $shops))) ||
                            !Shop::isFeatureActive()) {
                            $pro_array[$keys]['rules'] = $rules;
                            $pro_array[$keys]['rules_title'] = $rule['titles'];
                            $pro_array[$keys]['display_image'] = $display_image;
                            $pro_array[$keys]['products_list'] = $products_list;
                            $pro_array[$keys]['class_name'] = $position;
                            $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                        }
                    }
                } elseif ((int) $rule['id_rules'] == 9) {
                    $tagsProduct = array();

                    $tagsProduct = RelatedProductsRules::getrelatedwithTags(
                        (int) $rule['no_products'],
                        $rule['tags_value']
                    );

                    $products_list = $tagsProduct;

                    if (isset($products_list) && $products_list) {
                        foreach ($products_list as &$rel_product) {
                            $temp_product = new Product(
                                (int) $rel_product['id_related'],
                                true,
                                (int) $this->context->language->id
                            );
                            $cover = Product::getCover($rel_product['id_related']);
                            $rel_product['id_image'] = (int) $cover['id_image'];
                            $rel_product['attributes'] = array();
                            if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                $this->context->language->id
                            )) {
                                $rel_product['attributes'] = Product::getAttributesParams(
                                    (int) $rel_product['id_related'],
                                    $rel_product['id_combination']
                                );
                                if (array_key_exists($rel_product['id_combination'], $images)) {
                                    foreach ($images[$rel_product['id_combination']] as $image) {
                                        if (isset($image) && $image) {
                                            $rel_product['id_image'] = $image['id_image'];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $cover_image = Product::getCover($temp_product->id);
                                if ($cover_image) {
                                    $rel_product['id_image'] = $cover_image['id_image'];
                                }
                            }
                            $temp_product->price = Product::getPriceStatic(
                                $rel_product['id_related'],
                                true,
                                $rel_product['id_combination']
                            );
                            $rel_product['products'] = $temp_product;
                        }
                        $ids = array_column($products_list, 'id_related');
                        $ids = array_unique($ids);
                        $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                            $key = $key;
                            return in_array($value, array_keys($ids));
                        }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                        $position = (true === Tools::version_compare(
                            _PS_VERSION_,
                            '1.7',
                            '>='
                        )) ? ' ps_new' : '';
                        $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                        $this->context->smarty->assign('display_image', $display_image);
                        $this->context->smarty->assign('products_list', $products_list);
                        $this->context->smarty->assign('class_name', $position);
                        $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                        $this->context->smarty->assign(
                            'img_type',
                            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                        );
                        $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                        if ((Shop::isFeatureActive() && !empty($shops) &&
                            in_array($this->context->shop->id, explode(',', $shops))) ||
                            !Shop::isFeatureActive()) {
                            $pro_array[$keys]['rules'] = $rules;
                            $pro_array[$keys]['rules_title'] = $rule['titles'];
                            $pro_array[$keys]['display_image'] = $display_image;
                            $pro_array[$keys]['products_list'] = $products_list;
                            $pro_array[$keys]['class_name'] = $position;
                            $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                        }
                    }
                } elseif ((int) $rule['id_rules'] == 11) {
                    $product_lis = $rule['related_products_list'];
                    $product_lis = explode(',', $product_lis);
                    $getrelatedwithPro = array();
                    foreach ($product_lis as $key => $value) {
                        $getrelatedwithPro[$key]['id_related'] = (int) $value;
                        $getrelatedwithPro[$key]['id_combination'] = Product::getDefaultAttribute($value);
                    }
                    $products_list = $getrelatedwithPro;
                    if (isset($products_list) && $products_list) {
                        foreach ($products_list as &$rel_product) {
                            $temp_product = new Product(
                                (int) $rel_product['id_related'],
                                true,
                                (int) $this->context->language->id
                            );
                            $cover = Product::getCover($rel_product['id_related']);
                            $rel_product['id_image'] = (int) $cover['id_image'];
                            $rel_product['attributes'] = array();
                            if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                $this->context->language->id
                            )) {
                                $rel_product['attributes'] = Product::getAttributesParams(
                                    (int) $rel_product['id_related'],
                                    $rel_product['id_combination']
                                );
                                if (array_key_exists($rel_product['id_combination'], $images)) {
                                    foreach ($images[$rel_product['id_combination']] as $image) {
                                        if (isset($image) && $image) {
                                            $rel_product['id_image'] = $image['id_image'];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $cover_image = Product::getCover($temp_product->id);
                                if ($cover_image) {
                                    $rel_product['id_image'] = $cover_image['id_image'];
                                }
                            }
                            $temp_product->price = Product::getPriceStatic(
                                $rel_product['id_related'],
                                true,
                                $rel_product['id_combination']
                            );
                            $rel_product['products'] = $temp_product;
                        }
                        $ids = array_column($products_list, 'id_related');
                        $ids = array_unique($ids);
                        $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                            $key = $key;
                            return in_array($value, array_keys($ids));
                        }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                        $position = (true === Tools::version_compare(
                            _PS_VERSION_,
                            '1.7',
                            '>='
                        )) ? ' ps_new' : '';
                        $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                        $this->context->smarty->assign('display_image', $display_image);
                        $this->context->smarty->assign('products_list', $products_list);
                        $this->context->smarty->assign('class_name', $position);
                        $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                        $this->context->smarty->assign(
                            'img_type',
                            Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                        );
                        $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                        if ((Shop::isFeatureActive() && !empty($shops) &&
                            in_array($this->context->shop->id, explode(',', $shops))) ||
                            !Shop::isFeatureActive()) {
                            $pro_array[$keys]['rules'] = $rules;
                            $pro_array[$keys]['rules_title'] = $rule['titles'];
                            $pro_array[$keys]['display_image'] = $display_image;
                            $pro_array[$keys]['products_list'] = $products_list;
                            $pro_array[$keys]['class_name'] = $position;
                            $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                        }
                    }
                } elseif ((int) $rule['id_rules'] == 10) {
                    $id_language = $this->context->language->id;
                    $cat = $rule['category_box'];
                    if (!empty($cat)) {
                        $cat = explode(',', $cat);
                        $getrelatedwithCat = array();
                        $start_no = 0;
                        foreach ($cat as $key => $value) {
                            $id_category = $value;
                            $category = new Category($id_category, $id_language);
                            $nb = 0;
                            $result = $category->getProducts($id_language, 1, ($nb ? $nb : 10));
                            foreach ($result as $key => $value) {
                                $getrelatedwithCat[$start_no]['id_related'] = $value['id_product'];
                                $getrelatedwithCat[$start_no]['id_combination'] = Product::getDefaultAttribute(
                                    $value['id_product']
                                );
                                $start_no = $start_no + 1;
                            }
                        }
                        $allow_no = $rule['no_products'];
                        if ($allow_no != 0) {
                            $getrelatedwithCat = array_slice($getrelatedwithCat, 0, $allow_no);
                        }
                        $products_list = $getrelatedwithCat;
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    }
                }
            }
            $this->context->smarty->assign('pro_array', $pro_array);
            return $this->display(
                __FILE__,
                'relatedproducts_block_' .
                Configuration::get('RELATED_PRODUCTSBLOCK_VIEW') . '.tpl'
            );
        }
    }

    protected function displayRelatedProducts($id_product, $position = 'product')
    {
        if (!$id_product) {
            return false;
        } else {
            $rules = RelatedProductsRules::getAllRules();
            $pro_array = array();
            $cont = $this->getCurrentCont();
            
            if (!empty($rules)) {
                foreach ($rules as $keys => $rule) {
                    if (($cont == 'product' && $rule['id_page'] != 3) ||
                        (($cont == 'cart' || $cont == 'order') && $rule['id_page'] != 4)) {
                        continue;
                    }
                    $prods = explode(',', $rule['selected_prod']);
                    $cats = explode(',', $rule['selected_cat']);
                    $all_ids = Product::getProductCategoriesFull($id_product);
                    if ((int) $rule['id_rules'] == 1) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $bestsales = ProductSale::getBestSales(
                            $this->context->language->id,
                            null,
                            (int) $rule['no_products']
                        );
                        $products_list_bestsales = array();
                        if (isset($bestsales) && $bestsales) {
                            foreach ($bestsales as $key => $value) {
                                if ($rule['selected_opt'] == 2) {
                                    if (!empty($prods) && in_array($id_product, $prods)) {
                                        $products_list_bestsales[$key]['id_related'] = $value['id_product'];
                                        $products_list_bestsales[$key]['id_combination'] = $value[
                                            'id_product_attribute'
                                        ];
                                        $products_list_bestsales[$key]['id_rules'] = $rule['id_rules'];
                                    }
                                } elseif ($rule['selected_opt'] == 1) {
                                    if (!empty($cats) && !empty($all_ids)) {
                                        foreach ($all_ids as $id_cats) {
                                            if (in_array($id_cats['id_category'], $cats)) {
                                                $products_list_bestsales[$key]['id_related'] = $value[
                                                    'id_product'
                                                ];
                                                $products_list_bestsales[$key]['id_combination'] = $value[
                                                    'id_product_attribute'
                                                ];
                                                $products_list_bestsales[$key]['id_rules'] = $rule['id_rules'];
                                            }
                                        }
                                    }
                                } else {
                                    $products_list_bestsales[$key]['id_related'] = $value['id_product'];
                                    $products_list_bestsales[$key]['id_combination'] = $value[
                                        'id_product_attribute'
                                    ];
                                    $products_list_bestsales[$key]['id_rules'] = $rule['id_rules'];
                                }
                            }
                        }
                        $products_list = array_merge($products_list, $products_list_bestsales);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && !empty($products_list)) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) && in_array(
                                $this->context->shop->id,
                                explode(',', $shops)
                            )) || !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ($rule['id_rules'] == 2) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $dropPrice = Product::getPricesDrop(
                            $this->context->language->id,
                            null,
                            (int) $rule['no_products']
                        );
                        $products_list_droppricepro = array();
                        if (isset($dropPrice) && $dropPrice) {
                            foreach ($dropPrice as $key => $value) {
                                if ($rule['selected_opt'] == 2) {
                                    if (!empty($prods) && in_array(
                                        $id_product,
                                        $prods
                                    )) {
                                        $products_list_droppricepro[$key]['id_related'] = $value[
                                            'id_product'
                                        ];
                                        $products_list_droppricepro[$key]['id_combination'] = $value[
                                            'id_product_attribute'
                                        ];
                                    }
                                } elseif ($rule['selected_opt'] == 1) {
                                    if (!empty($cats) && !empty($all_ids)) {
                                        foreach ($all_ids as $id_cats) {
                                            if (in_array($id_cats['id_category'], $cats)) {
                                                $products_list_droppricepro[$key]['id_related'] = $value[
                                                    'id_product'
                                                ];
                                                $products_list_droppricepro[$key]['id_combination'] = $value[
                                                    'id_product_attribute'
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    $products_list_droppricepro[$key]['id_related'] = $value[
                                        'id_product'
                                    ];
                                    $products_list_droppricepro[$key]['id_combination'] = $value[
                                        'id_product_attribute'
                                    ];
                                }
                            }
                        }
                        $products_list = array_merge($products_list, $products_list_droppricepro);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter(
                                $products_list,
                                function ($key, $value) use ($ids) {
                                    $key = $key;
                                    return in_array($value, array_keys($ids));
                                },
                                ARRAY_FILTER_USE_BOTH
                            ); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array(
                                    $this->context->shop->id,
                                    explode(',', $shops)
                                )) || !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 3) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $newpro = Product::getNewProducts(
                            $this->context->language->id,
                            null,
                            (int) $rule['no_products']
                        );
                        $products_list_newpro = array();
                        if (isset($newpro) && $newpro) {
                            foreach ($newpro as $key => $value) {
                                if ($rule['selected_opt'] == 2) {
                                    if (!empty($prods) && in_array($id_product, $prods)) {
                                        $products_list_newpro[$key]['id_related'] = $value[
                                            'id_product'
                                        ];
                                        $products_list_newpro[$key]['id_combination'] = $value[
                                            'id_product_attribute'
                                        ];
                                    }
                                } elseif ($rule['selected_opt'] == 1) {
                                    if (!empty($cats) && !empty($all_ids)) {
                                        foreach ($all_ids as $id_cats) {
                                            if (in_array($id_cats['id_category'], $cats)) {
                                                $products_list_newpro[$key]['id_related'] = $value[
                                                    'id_product'
                                                ];
                                                $products_list_newpro[$key]['id_combination'] = $value[
                                                    'id_product_attribute'
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    $products_list_newpro[$key]['id_related'] = $value[
                                        'id_product'
                                    ];
                                    $products_list_newpro[$key]['id_combination'] = $value[
                                        'id_product_attribute'
                                    ];
                                }
                            }
                        }
                        $products_list = array_merge(
                            $products_list,
                            $products_list_newpro
                        );
                        $current_product = new Product(
                            $id_product,
                            true,
                            $this->context->language->id
                        );
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter(
                                $products_list,
                                function ($key, $value) use ($ids) {
                                    $key = $key;
                                    return in_array($value, array_keys($ids));
                                },
                                ARRAY_FILTER_USE_BOTH
                            ); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array(
                                    $this->context->shop->id,
                                    explode(',', $shops)
                                )) || !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 4) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $products_list_topViewed = array();

                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $products_list_topViewed = RelatedProductsRules::getTopViewedProducts(
                                    $rule['no_products']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $products_list_topViewed = RelatedProductsRules::getTopViewedProducts(
                                            $rule['no_products']
                                        );
                                    }
                                }
                            }
                        } else {
                            $products_list_topViewed = RelatedProductsRules::getTopViewedProducts(
                                $rule['no_products']
                            );
                        }
                        $products_list = array_merge(
                            $products_list,
                            $products_list_topViewed
                        );
                        $current_product = new Product(
                            $id_product,
                            true,
                            $this->context->language->id
                        );
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter(
                                $products_list,
                                function ($key, $value) use ($ids) {
                                    $key = $key;
                                    return in_array($value, array_keys($ids));
                                },
                                ARRAY_FILTER_USE_BOTH
                            ); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 5) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $products_list_samecat = array();
                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $products_list_samecat = RelatedProductsRules::getSameCategroyProducts(
                                    $id_product,
                                    (int) $rule['no_products']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $products_list_samecat = RelatedProductsRules::getSameCategroyProducts(
                                            $id_product,
                                            (int) $rule['no_products']
                                        );
                                    }
                                }
                            }
                        } else {
                            $products_list_samecat = RelatedProductsRules::getSameCategroyProducts(
                                $id_product,
                                (int) $rule['no_products']
                            );
                        }
                        $products_list = array_merge($products_list, $products_list_samecat);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 6) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $samebrandPro = array();
                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $samebrandPro = RelatedProductsRules::getSameBrandProducts(
                                    $id_product,
                                    (int) $rule['no_products']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $samebrandPro = RelatedProductsRules::getSameBrandProducts(
                                            $id_product,
                                            (int) $rule['no_products']
                                        );
                                    }
                                }
                            }
                        } else {
                            $samebrandPro = RelatedProductsRules::getSameBrandProducts(
                                $id_product,
                                (int) $rule['no_products']
                            );
                        }
                        $products_list = array_merge($products_list, $samebrandPro);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 7) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $samebrandcatPro = array();
                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $samebrandcatPro = RelatedProductsRules::getSameBrandcategoryProducts(
                                    $id_product,
                                    (int) $rule['no_products']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $samebrandcatPro = RelatedProductsRules::getSameBrandcategoryProducts(
                                            $id_product,
                                            (int) $rule['no_products']
                                        );
                                    }
                                }
                            }
                        } else {
                            $samebrandcatPro = RelatedProductsRules::getSameBrandcategoryProducts(
                                $id_product,
                                (int) $rule['no_products']
                            );
                        }
                        $products_list = array_merge($products_list, $samebrandcatPro);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 8) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $custboughtpro = array();
                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $custboughtpro = RelatedProductsRules::getrelatedCustomerBoughtPro(
                                    $id_product,
                                    (int) $rule['no_products']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $custboughtpro = RelatedProductsRules::getrelatedCustomerBoughtPro(
                                            $id_product,
                                            (int) $rule['no_products']
                                        );
                                    }
                                }
                            }
                        } else {
                            $custboughtpro = RelatedProductsRules::getrelatedCustomerBoughtPro(
                                $id_product,
                                (int) $rule['no_products']
                            );
                        }
                        $products_list = array_merge($products_list, $custboughtpro);
                        $current_product = new Product(
                            $id_product,
                            true,
                            $this->context->language->id
                        );
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 9) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $tagsProduct = array();
                        if ($rule['selected_opt'] == 2) {
                            if (!empty($prods) && in_array($id_product, $prods)) {
                                $tagsProduct = RelatedProductsRules::getrelatedwithTags(
                                    (int) $rule['no_products'],
                                    $rule['tags_value']
                                );
                            }
                        } elseif ($rule['selected_opt'] == 1) {
                            if (!empty($cats) && !empty($all_ids)) {
                                foreach ($all_ids as $id_cats) {
                                    if (in_array($id_cats['id_category'], $cats)) {
                                        $tagsProduct = RelatedProductsRules::getrelatedwithTags(
                                            (int) $rule['no_products'],
                                            $rule['tags_value']
                                        );
                                    }
                                }
                            }
                        } else {
                            $tagsProduct = RelatedProductsRules::getrelatedwithTags(
                                (int) $rule['no_products'],
                                $rule['tags_value']
                            );
                        }
                        $products_list = array_merge($products_list, $tagsProduct);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 11) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $product_lis = $rule['related_products_list'];
                        $product_lis = explode(',', $product_lis);
                        $getrelatedwithPro = array();
                        foreach ($product_lis as $key => $value) {
                            if ($rule['selected_opt'] == 2) {
                                if (!empty($prods) && in_array($id_product, $prods)) {
                                    $getrelatedwithPro[$key]['id_related'] = (int) $value;
                                    $getrelatedwithPro[$key]['id_combination'] = Product::getDefaultAttribute(
                                        $value
                                    );
                                }
                            } elseif ($rule['selected_opt'] == 1) {
                                if (!empty($cats) && !empty($all_ids)) {
                                    foreach ($all_ids as $id_cats) {
                                        if (in_array($id_cats['id_category'], $cats)) {
                                            $getrelatedwithPro[$key]['id_related'] = (int) $value;
                                            $getrelatedwithPro[$key]['id_combination'] = Product::getDefaultAttribute(
                                                $value
                                            );
                                        }
                                    }
                                }
                            } else {
                                $getrelatedwithPro[$key]['id_related'] = (int) $value;
                                $getrelatedwithPro[$key]['id_combination'] = Product::getDefaultAttribute($value);
                            }
                        }
                        $products_list = array_merge($products_list, $getrelatedwithPro);
                        $current_product = new Product($id_product, true, $this->context->language->id);
                        $current_cover = Product::getCover($id_product);
                        if (isset($products_list) && $products_list) {
                            foreach ($products_list as &$rel_product) {
                                $temp_product = new Product(
                                    (int) $rel_product['id_related'],
                                    true,
                                    (int) $this->context->language->id
                                );
                                $cover = Product::getCover($rel_product['id_related']);
                                $rel_product['id_image'] = (int) $cover['id_image'];
                                $rel_product['attributes'] = array();
                                if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                    $this->context->language->id
                                )) {
                                    $rel_product['attributes'] = Product::getAttributesParams(
                                        (int) $rel_product['id_related'],
                                        $rel_product['id_combination']
                                    );
                                    if (array_key_exists($rel_product['id_combination'], $images)) {
                                        foreach ($images[$rel_product['id_combination']] as $image) {
                                            if (isset($image) && $image) {
                                                $rel_product['id_image'] = $image['id_image'];
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $cover_image = Product::getCover($temp_product->id);
                                    if ($cover_image) {
                                        $rel_product['id_image'] = $cover_image['id_image'];
                                    }
                                }
                                $temp_product->price = Product::getPriceStatic(
                                    $rel_product['id_related'],
                                    true,
                                    $rel_product['id_combination']
                                );
                                $rel_product['products'] = $temp_product;
                            }
                            $ids = array_column($products_list, 'id_related');
                            $ids = array_unique($ids);
                            $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                $key = $key;
                                return in_array($value, array_keys($ids));
                            }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                            $position = (true === Tools::version_compare(
                                _PS_VERSION_,
                                '1.7',
                                '>='
                            )) ? ' ps_new' : '';
                            $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                            $this->context->smarty->assign('display_image', $display_image);
                            $this->context->smarty->assign('current_cover', $current_cover);
                            $this->context->smarty->assign('current_product', $current_product);
                            $this->context->smarty->assign('products_list', $products_list);
                            $this->context->smarty->assign('class_name', $position);
                            $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                            $this->context->smarty->assign(
                                'img_type',
                                Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                            );
                            $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                            if ((Shop::isFeatureActive() && !empty($shops) &&
                                in_array($this->context->shop->id, explode(',', $shops))) ||
                                !Shop::isFeatureActive()) {
                                $pro_array[$keys]['rules'] = $rules;
                                $pro_array[$keys]['rules_title'] = $rule['titles'];
                                $pro_array[$keys]['display_image'] = $display_image;
                                $pro_array[$keys]['current_cover'] = $current_cover;
                                $pro_array[$keys]['current_product'] = $current_product;
                                $pro_array[$keys]['products_list'] = $products_list;
                                $pro_array[$keys]['class_name'] = $position;
                                $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                            }
                        }
                    } elseif ((int) $rule['id_rules'] == 10) {
                        $products_list = RelatedproductsModel::selectRow($id_product);
                        $id_language = $this->context->language->id;
                        $cat = $rule['category_box'];
                        if (!empty($cat)) {
                            $cat = explode(',', $cat);
                            $getrelatedwithCat = array();
                            $start_no = 0;
                            foreach ($cat as $key => $value) {
                                $id_category = $value;
                                $category = new Category($id_category, $id_language);
                                $nb = 0;
                                $result = $category->getProducts($id_language, 1, ($nb ? $nb : 10));
                                foreach ($result as $key => $value) {
                                    if ($rule['selected_opt'] == 2) {
                                        if (!empty($prods) && in_array($id_product, $prods)) {
                                            $getrelatedwithCat[$start_no]['id_related'] = $value['id_product'];
                                            $getrelatedwithCat[$start_no][
                                                'id_combination'
                                            ] = Product::getDefaultAttribute(
                                                $value['id_product']
                                            );
                                            $start_no = $start_no + 1;
                                        }
                                    } elseif ($rule['selected_opt'] == 1) {
                                        if (!empty($cats) && !empty($all_ids)) {
                                            foreach ($all_ids as $id_cats) {
                                                if (in_array($id_cats['id_category'], $cats)) {
                                                    $getrelatedwithCat[$start_no]['id_related'] = $value['id_product'];
                                                    $getrelatedwithCat[$start_no][
                                                        'id_combination'
                                                    ] = Product::getDefaultAttribute(
                                                        $value['id_product']
                                                    );
                                                    $start_no = $start_no + 1;
                                                }
                                            }
                                        }
                                    } else {
                                        $getrelatedwithCat[$start_no]['id_related'] = $value['id_product'];
                                        $getrelatedwithCat[$start_no]['id_combination'] = Product::getDefaultAttribute(
                                            $value['id_product']
                                        );
                                        $start_no = $start_no + 1;
                                    }
                                }
                            }
                            $allow_no = $rule['no_products'];
                            if ($allow_no != 0) {
                                $getrelatedwithCat = array_slice($getrelatedwithCat, 0, $allow_no);
                            }
                            $products_list = array_merge($products_list, $getrelatedwithCat);
                            $current_product = new Product($id_product, true, $this->context->language->id);
                            $current_cover = Product::getCover($id_product);
                            if (isset($products_list) && $products_list) {
                                foreach ($products_list as &$rel_product) {
                                    $temp_product = new Product(
                                        (int) $rel_product['id_related'],
                                        true,
                                        (int) $this->context->language->id
                                    );
                                    $cover = Product::getCover($rel_product['id_related']);
                                    $rel_product['id_image'] = (int) $cover['id_image'];
                                    $rel_product['attributes'] = array();
                                    if ($rel_product['id_combination'] && $images = $temp_product->getCombinationImages(
                                        $this->context->language->id
                                    )) {
                                        $rel_product['attributes'] = Product::getAttributesParams(
                                            (int) $rel_product['id_related'],
                                            $rel_product['id_combination']
                                        );
                                        if (array_key_exists($rel_product['id_combination'], $images)) {
                                            foreach ($images[$rel_product['id_combination']] as $image) {
                                                if (isset($image) && $image) {
                                                    $rel_product['id_image'] = $image['id_image'];
                                                    break;
                                                }
                                            }
                                        }
                                    } else {
                                        $cover_image = Product::getCover($temp_product->id);
                                        if ($cover_image) {
                                            $rel_product['id_image'] = $cover_image['id_image'];
                                        }
                                    }
                                    $temp_product->price = Product::getPriceStatic(
                                        $rel_product['id_related'],
                                        true,
                                        $rel_product['id_combination']
                                    );
                                    $rel_product['products'] = $temp_product;
                                }
                                $ids = array_column($products_list, 'id_related');
                                $ids = array_unique($ids);
                                $products_list = array_filter($products_list, function ($key, $value) use ($ids) {
                                    $key = $key;
                                    return in_array($value, array_keys($ids));
                                }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
                                $position = (true === Tools::version_compare(
                                    _PS_VERSION_,
                                    '1.7',
                                    '>='
                                )) ? ' ps_new' : '';
                                $display_image = Configuration::get('RELATED_PRODUCT_IMAGE');
                                $this->context->smarty->assign('display_image', $display_image);
                                $this->context->smarty->assign('current_cover', $current_cover);
                                $this->context->smarty->assign('current_product', $current_product);
                                $this->context->smarty->assign('products_list', $products_list);
                                $this->context->smarty->assign('class_name', $position);
                                $this->context->smarty->assign('id_lang', $this->context->cookie->id_lang);
                                $this->context->smarty->assign(
                                    'img_type',
                                    Configuration::get('RELATED_PRODUCTSBLOCK_IMAGE_TYPES')
                                );
                                $shops = Configuration::get('RELATED_PRODUCTS_SHOP');
                                if ((Shop::isFeatureActive() && !empty($shops) &&
                                    in_array($this->context->shop->id, explode(',', $shops))) ||
                                    !Shop::isFeatureActive()) {
                                    $pro_array[$keys]['rules'] = $rules;
                                    $pro_array[$keys]['rules_title'] = $rule['titles'];
                                    $pro_array[$keys]['display_image'] = $display_image;
                                    $pro_array[$keys]['current_cover'] = $current_cover;
                                    $pro_array[$keys]['current_product'] = $current_product;
                                    $pro_array[$keys]['products_list'] = $products_list;
                                    $pro_array[$keys]['class_name'] = $position;
                                    $pro_array[$keys]['id_lang'] = $this->context->cookie->id_lang;
                                }
                            }
                        }
                    }
                }
                $this->context->smarty->assign('pro_array', $pro_array);
                return $this->display(
                    __FILE__,
                    'relatedproducts_block_' .
                    Configuration::get('RELATED_PRODUCTSBLOCK_VIEW') . '.tpl'
                );
            }
        }
    }

    public function upgradeRelatedProductsModule()
    {
        $subtab2 = new Tab();
        $subtab2->id_parent = Tab::getIdFromClassName($this->tab_class);
        $subtab2->module = $this->name;
        $subtab2->class_name = 'AdminRelatedProductsVisibility';
        $subtab2->name[(int) Configuration::get('PS_LANG_DEFAULT')] = $this->l(
            'Related Products Visibility Criteria'
        );
        $add_tab = $subtab2->add();
        $default_size = Configuration::updateValue(
            'RELATED_PRODUCTSBLOCK_IMAGE_TYPES',
            ImageType::getFormattedName('home')
        );
        $new_table = RelatedproductsModel::addNewValuesForUpgrade();
        return $add_tab . $new_table . $default_size;
    }

    public function upgradeV220()
    {
        $this->registerHook('displayHome');
        $this->registerHook('displayFmmRelatedProducts');
        return RelatedproductsModel::upgradeV220();
    }


    public function getCurrentCont()
    {
        return Dispatcher::getInstance()->getController();
    }

    public function getCurrentCat()
    {
        return (int) Tools::getValue('id_category');
    }
}
