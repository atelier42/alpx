<?php
/**
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

require_once(dirname(__FILE__).'/classes/AdditionalProductListing.php');

class Stadditionalproductlisting extends Module implements WidgetInterface
{
    public $hooks = array();
    public $types = array();
    public $productIds = array();
    public $currentProductId = null;
    public function __construct()
    {
        $this->name = 'stadditionalproductlisting';
        $this->version = '5.1.1';
        $this->author = 'Sathi';
        $this->tab = 'front_office_features';
        $this->need_instance = 0;
        $this->module_key = '7b910eba71a573ccaf1d058c3e42223f';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Extra Product Listing');
        $this->description = $this->l('Display multi features extra products list by category, manufacturer, supplier, recent viewed, chosen etc. at multiple front office positions.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:'.$this->name.'/views/templates/hook/stadditionalproductlisting.tpl';

        $this->hooks = array(
            array(
                'id' => 'displayTop',
                'name' => $this->l('Header'),
            ),
            array(
                'id' => 'displayFooter',
                'name' => $this->l('Footer'),
            ),
            array(
                'id' => 'displayFooterBefore',
                'name' => $this->l('Before footer'),
            ),
            array(
                'id' => 'displayHome',
                'name' => $this->l('Home page'),
            ),
            array(
                'id' => 'displaySearch',
                'name' => $this->l('Not found page'),
            ),
            array(
                'id' => 'displayLeftColumn',
                'name' => $this->l('Left column'),
            ),
            array(
                'id' => 'displayRightColumn',
                'name' => $this->l('Right column'),
            ),
            array(
                'id' => 'displayContentWrapperTop',
                'name' => $this->l('Before content'),
            ),
            array(
                'id' => 'displayContentWrapperBottom',
                'name' => $this->l('After content'),
            ),
            array(
                'id' => 'displayFooterProduct',
                'name' => $this->l('Product page'),
            ),
            array(
                'id' => 'displayShoppingCartFooter',
                'name' => $this->l('After shopping cart list'),
            ),
            array(
                'id' => 'displayOrderConfirmation1',
                'name' => $this->l('Order confirmation page'),
            ),
            array(
                'id' => 'displayOrderDetail',
                'name' => $this->l('Customer order detail page'),
            ),
            array(
                'id' => 'displayCMSDisputeInformation',
                'name' => $this->l('Information page'),
            )
        );
        $this->types = array(
            array(
                'id' => 1,
                'name' => $this->l('Category'),
            ),
            array(
                'id' => 2,
                'name' => $this->l('Manufacturer'),
            ),
            array(
                'id' => 3,
                'name' => $this->l('Supplier'),
            ),
            array(
                'id' => 4,
                'name' => $this->l('Specific product'),
            ),
            array(
                'id' => 5,
                'name' => $this->l('Recent viewed'),
            ),
            array(
                'id' => 6,
                'name' => $this->l('Related products of previous order'),
            ),
            array(
                'id' => 7,
                'name' => $this->l('Related products (Product page only)'),
            ),
            array(
                'id' => 8,
                'name' => $this->l('Same category (Product page only)'),
            )
        );
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook(
                array(
                    'displayTop',
                    'displayHome',
                    'displayHeader',
                    'displayFooter',
                    'displaySearch',
                    'displayLeftColumn',
                    'displayRightColumn',
                    'displayOrderDetail',
                    'displayFooterBefore',
                    'displayFooterProduct',
                    'displayProductButtons', //Former version of displayProductAdditionalInfo
                    'displayBackOfficeHeader',
                    'displayContentWrapperTop',
                    'displayOrderConfirmation1',
                    'displayRightColumnProduct',  //For product page right column
                    'displayShoppingCartFooter',
                    'displayContentWrapperBottom',
                    'displayProductAdditionalInfo',
                    'displayCMSDisputeInformation',
                )
            )
            || !$this->createTable()
            || !$this->callInstallTab()
        ) {
            return false;
        }
        return true;
    }
    
    public function callInstallTab()
    {
        $this->installTab('AdminAdditionalProductListing', $this->l('Extra Product Listing'), 'AdminParentThemes');

        return true;
    }

    public function installTab($className, $tabName, $tabParentName = false)
    {
        $tabParentId = 0;
        if ($tabParentName) {
            $this->createModuleTab($className, $tabName, $tabParentId, $tabParentName);
        } else {
            $this->createModuleTab($className, $tabName, $tabParentId);
        }
    }

    public function createModuleTab($className, $tabName, $tabParentId, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if ($tabParentName) {
            $tab->id_parent = (int) Tab::getIdFromClassName($tabParentName);
        } else {
            $tab->id_parent = $tabParentId;
        }

        $tab->module = $this->name;

        return $tab->add();
    }

    public function createTable()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        return Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'st_additionalproductlisting` (
				id_additionalproductlisting int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				hook varchar(255) DEFAULT NULL,
				id_shop int(11) UNSIGNED,
				filter_type int(11) UNSIGNED,
				filter_data text,
				is_carousel tinyint(1) UNSIGNED,
                width int(11) UNSIGNED,
                auto tinyint(1) UNSIGNED,
                nav tinyint(1) UNSIGNED,
                pause tinyint(1) UNSIGNED,
                pager tinyint(1) UNSIGNED,
                color varchar(55) DEFAULT NULL,
                product_count int(11) UNSIGNED,
				active tinyint(1) UNSIGNED,
				date_add datetime,
				date_upd datetime,
				PRIMARY KEY ( id_additionalproductlisting ))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;') 
            && Db::getInstance()->execute('CREATE TABLE `'._DB_PREFIX_.'st_additionalproductlisting_lang` (
                id_additionalproductlisting int(11) UNSIGNED,
                id_lang int(11) UNSIGNED,
                name varchar(255) DEFAULT NULL,
                description text DEFAULT NULL)
                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
    }

    public function dropTable()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'st_additionalproductlisting`, `'
        ._DB_PREFIX_.'st_additionalproductlisting_lang`');
    }

    public function uninstall()
    {
        return (parent::uninstall()
                && $this->dropTable()
            );
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerJavascript('st_owl_carousel_min_js', 'modules/'.
        $this->name.'/views/js/owl.carousel.min.js', array('position' => 'bottom', 'priority' => 150));
        $this->context->controller->registerStylesheet('stadditionalproductlisting_css', 'modules/'.
        $this->name.'/views/css/stadditionalproductlisting.css', array('media' => 'all', 'priority' => 150));
        $this->context->controller->registerJavascript('stadditionalproductlisting_js', 'modules/'.
        $this->name.'/views/js/stadditionalproductlisting.js', array('position' => 'bottom', 'priority' => 150));
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/formconfig.tpl');
    }

    protected function addViewedProduct($idProduct)
    {
        $arr = array();
        if (isset($this->context->cookie->viewed)) {
            $arr = explode(',', $this->context->cookie->viewed);
        }
        if (!in_array($idProduct, $arr)) {
            $arr[] = $idProduct;
            $this->context->cookie->viewed = trim(implode(',', $arr), ',');
            $this->context->cookie->write();
        }
    }

    protected function getViewedProductIds($limit)
    {
        $arr = array_reverse(explode(',', $this->context->cookie->viewed));
        if (null !== $this->currentProductId && in_array($this->currentProductId, $arr)) {
            $arr = array_diff($arr, array($this->currentProductId));
        }
        return array_slice($arr, 0, $limit);
    }

    protected function getOrderProducts($limit)
    {
        $productIds = array();
        if ($this->context->customer) {
            $customer = new Customer($this->context->customer->id);
            $products = $customer->getBoughtProducts();
            foreach ($products as $product) {
                $productIds = array_merge($productIds, AdditionalProductListing::getRelatedProductIds(
                    $product['product_id'],
                    $limit
                ));
                $productIds = array_unique($productIds);
                if (count($productIds) >= $limit) {
                    break;
                }
            }
        }
        return $productIds;
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (isset($configuration['product']['id_product'])) {
            $this->currentProductId = $configuration['product']['id_product'];
        }

        if ('displayRightColumnProduct' === $hookName) {
            $hookName = 'displayRightColumn';
        }

        if ('displayProductButtons' === $hookName || 'displayProductAdditionalInfo' === $hookName) {
            $this->addViewedProduct($this->currentProductId);
            return;
        }

        $widgets = $this->getWidgetVariables($hookName, $configuration);

        if (empty($widgets)) {
            return false;
        }
        $html = '';
        foreach ($widgets as $widget) {
            $this->smarty->assign($widget);
            $html .= $this->fetch($this->templateFile);
        }
        return $html;
    }
    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        $idLang = (int)$this->context->language->id;
        $widgets = AdditionalProductListing::getHookInfo($hookName, $idLang, (int)$this->context->shop->id);
        foreach ($widgets as &$widget) {
            if (!$widget['product_count']) {
                $widget['product_count'] = 8;
            }
            if ($widget['filter_type'] == 1) {
                $this->productIds = AdditionalProductListing::getProductByCatIds(
                    (array)Tools::unSerialize($widget['filter_data']),
                    $widget['product_count'],
                    $idLang
                );
            }
            if ($widget['filter_type'] == 2) {
                $this->productIds = AdditionalProductListing::getProductByManufacturerIds(
                    (array)Tools::unSerialize($widget['filter_data']),
                    $widget['product_count'],
                    $idLang
                );
            }

            if ($widget['filter_type'] == 3) {
                $this->productIds = AdditionalProductListing::getProductBySupplierIds(
                    (array)Tools::unSerialize($widget['filter_data']),
                    $widget['product_count'],
                    $idLang
                );
            }

            if ($widget['filter_type'] == 4) {
                $this->productIds = array_slice(
                    (array)Tools::unSerialize($widget['filter_data']),
                    0,
                    $widget['product_count']
                );
            }

            if ($widget['filter_type'] == 5) {
                $this->productIds = $this->getViewedProductIds($widget['product_count']);
            }

            if ($widget['filter_type'] == 6) {
                $this->productIds = $this->getOrderProducts($widget['product_count']);
            }

            if ($widget['filter_type'] == 7 && Tools::getValue('id_product')) {
                $this->productIds = AdditionalProductListing::getRelatedProductIds(
                    (int)Tools::getValue('id_product'),
                    $widget['product_count']
                );
            }

            if ($widget['filter_type'] == 8 && Tools::getValue('id_product')) {
                $categories = Product::getProductCategories((int)Tools::getValue('id_product'));
                $this->productIds = AdditionalProductListing::getProductByCatIds(
                    $categories,
                    $widget['product_count'],
                    $idLang
                );
            }

            $this->productIds = array_filter($this->productIds);
            $this->productIds = array_unique($this->productIds);

            $widget['products'] = array();

            if ($this->productIds) {
                $assembler = new ProductAssembler($this->context);

                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = new ProductListingPresenter(
                    new ImageRetriever(
                        $this->context->link
                    ),
                    $this->context->link,
                    new PriceFormatter(),
                    new ProductColorsRetriever(),
                    $this->context->getTranslator()
                );

                $products = array();

                foreach ($this->productIds as $id_product) {
                    $row = Db::getInstance()->getRow('
                        SELECT `id_product`
                        FROM `' . _DB_PREFIX_ . 'product` p
                        WHERE p.id_product = ' . pSQL($id_product) , false);

                    if($row['id_product']){
                        $products[] = $presenter->present(
                            $presentationSettings,
                            $assembler->assembleProduct(array('id_product' => $id_product)),
                            $this->context->language
                        );
                    }
                }

                $widget['products'] = $products;

                if ($widget['auto']) {
                    $widget['auto'] = 'true';
                } else {
                    $widget['auto'] = 'false';
                }
                if ($widget['pause']) {
                    $widget['pause'] = 'true';
                } else {
                    $widget['pause'] = 'false';
                }
                if ($widget['nav']) {
                    $widget['nav'] = 'true';
                } else {
                    $widget['nav'] = 'false';
                }
                if ($widget['pager']) {
                    $widget['pager'] = 'true';
                } else {
                    $widget['pager'] = 'false';
                }
            }
        }
        return $widgets;
    }
    public function getContent()
    {
        return Tools::redirectAdmin($this->context->link->getAdminLink('AdminAdditionalProductListing'));
    }
    
    public function elementHtml($element, $data = array())
    {
        $this->context->smarty->assign(
            array(
                'element_type' => $element,
                'st_data' => $data
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.
        '/views/templates/hook/element_html.tpl');
    }
}
