<?php
/**
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
*
* @author    FMM Modules
* @copyright FMM Modules
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
if (!defined('_MYSQL_ENGINE_')) {
    define('_MYSQL_ENGINE_', 'MyISAM');
}

require_once _PS_MODULE_DIR_.'productlabelsandstickers/models/Stickers.php';
require_once _PS_MODULE_DIR_.'productlabelsandstickers/models/ProductLabel.php';
require_once _PS_MODULE_DIR_.'productlabelsandstickers/models/Rules.php';
class ProductLabelsandStickers extends Module
{
    private $tab_parent_class = null;
    private $tab_class  = 'AdminFmmStickers';
    private $tab_module = 'productlabelsandstickers';
    protected $start_time = 0;
    protected $max_execution_time = 7200;
    private $image_types = array();
    private $y_align;
    private $x_align;
    private $transparency;

    public function __construct()
    {
        if (!defined('SEPARATOR')) {
            $os = PHP_OS;
            switch ($os) {
                case 'Linux':
                    define('SEPARATOR', '/');
                    break;
                case 'Windows':
                    define('SEPARATOR', '\\');
                    break;
                default:
                    define('SEPARATOR', '/');
                    break;
            }
        }

        $this->bootstrap    = true;
        $this->display      = 'view';
        $this->name         = 'productlabelsandstickers';
        $this->tab          = 'front_office_features';
        $this->version      = '2.2.3';
        $this->author       = 'FMM Modules';
        $this->module_key   = 'cf55a90d5788ef6b05690b714337f2f7';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';

        parent::__construct();

        $this->displayName  = $this->l('Product labels and Stickers');
        $this->description  = $this->l('Add sticker(s) to product images.');
        foreach (ImageType::getImagesTypes('products') as $type) {
            $this->image_types[] = $type;
        }
    }


    public function install()
    {
        if (!$this->existsTab($this->tab_class)) {
            if (!$this->addTab($this->tab_class, 0)) {
                return false;
            }
        }
        mkdir(_PS_IMG_DIR_.'stickers', 0777, true);
        if (!parent::install()
            || !$this->installDb()
            || !$this->registerHook('displayCatalogListing')
            || !$this->registerHook('displayProductListFunctionalButtons')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('header')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayProductListReviews')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('displayProductPageCss')
            || !$this->registerHook('displayFooterProduct')) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!$this->removeTab($this->tab_class)) {
            return false;
        }

        if (!$this->uninstallDb()) {
            return false;
        }

        rename(_PS_IMG_DIR_.'stickers', _PS_IMG_DIR_.'stickers'.rand(pow(10, 3 - 1), pow(10, 3) - 1));
        $this->unregisterHook('displayAdminProductsExtra');
        $this->unregisterHook('actionProductUpdate');
        parent::uninstall();
        return true;
    }

    private function addTab($tab_class, $id_parent)
    {
        $tab = new Tab();
        $tab->class_name = $tab_class;
        $tab->id_parent = $id_parent;
        $tab->module = $this->tab_module;
        $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Product labels and Stickers');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $tab->icon = 'filter';
        }
        $tab->add();

        $subtab = new Tab();
        $subtab->class_name = 'AdminStickers';
        $subtab->id_parent = Tab::getIdFromClassName($tab_class);
        $subtab->module = $this->tab_module;
        $subtab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Manage Image Stickers');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $subtab->icon = 'filter';
        }
        $subtab->add();

        $fifthtab = new Tab();
        $fifthtab->class_name = 'AdminTextStickers';
        $fifthtab->id_parent = Tab::getIdFromClassName($tab_class);
        $fifthtab->module = $this->tab_module;
        $fifthtab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Manage Text Stickers');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $fifthtab->icon = 'filter';
        }
        $fifthtab->add();
        
        $thirdtab = new Tab();
        $thirdtab->class_name = 'AdminStickersBanners';
        $thirdtab->id_parent = Tab::getIdFromClassName($tab_class);
        $thirdtab->module = $this->tab_module;
        $thirdtab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Manage Text Banners');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $thirdtab->icon = 'filter';
        }
        $thirdtab->add();

        $fourthtab = new Tab();
        $fourthtab->class_name = 'AdminStickersRules';
        $fourthtab->id_parent = Tab::getIdFromClassName($tab_class);
        $fourthtab->module = $this->tab_module;
        $fourthtab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Manage Sticker Rules');
        if (true === Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $fourthtab->icon = 'filter';
        }
        $fourthtab->add();
        return true;
    }

    private function removeTab($tab_class)
    {
        $idTab = Tab::getIdFromClassName($tab_class);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }

        $idTab1 = Tab::getIdFromClassName('AdminStickers');
        if ($idTab1 != 0) {
            $tab = new Tab($idTab1);
            $tab->delete();
            return true;
        }

        $idTab2 = Tab::getIdFromClassName('AdminStickersBanners');
        if ($idTab2 != 0) {
            $tab = new Tab($idTab2);
            $tab->delete();
            return true;
        }

        $idTab3 = Tab::getIdFromClassName('AdminStickersRules');
        if ($idTab3 != 0) {
            $tab = new Tab($idTab3);
            $tab->delete();
            return true;
        }
        
        $idTab4 = Tab::getIdFromClassName('AdminTextStickers');
        if ($idTab4 != 0) {
            $tab = new Tab($idTab4);
            $tab->delete();
            return true;
        }
        return true;
    }

    private function existsTab($tab_class)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT id_tab AS id
            FROM `'._DB_PREFIX_.'tab` t WHERE LOWER(t.`class_name`) = \''.pSQL($tab_class).'\'');
        if (count($result) == 0) {
            return false;
        }
        return true;
    }

    private function installDb()
    {
        Configuration::updateValue('sticker_type_val', 1);
        $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers';
        Db::getInstance()->execute($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'fmm_stickers(
                `sticker_id` int(11) NOT NULL auto_increment,
                `sticker_name` varchar(255) character set utf8 default NULL,
                `sticker_size` varchar(255) default NULL,
                `sticker_opacity` varchar(255) default NULL,
                `sticker_size_list` varchar(255) default NULL,
                `sticker_image` varchar(255) default NULL,
                `x_align` varchar(255) default NULL,
                `y_align` varchar(255) default NULL,
                `transparency` int(11) default NULL,
                `medium_width` int(11) default NULL,
                `medium_height` int(11) default NULL,
                `medium_x` int(11) default NULL,
                `medium_y` int(11) default NULL,
                `small_width` int(11) default NULL,
                `small_height` int(11) default NULL,
                `small_x` int(11) default NULL,
                `small_y` int(11) default NULL,
                `thickbox_width` int(11) default NULL,
                `thickbox_height` int(11) default NULL,
                `thickbox_x` int(11) default NULL,
                `thickbox_y` int(11) default NULL,
                `large_width` int(11) default NULL,
                `large_height` int(11) default NULL,
                `large_x` int(11) default NULL,
                `large_y` int(11) default NULL,
                `home_width` int(11) default NULL,
                `home_height` int(11) default NULL,
                `home_x` int(11) default NULL,
                `home_y` int(11) default NULL,
                `cart_width` int(11) default NULL,
                `cart_height` int(11) default NULL,
                `cart_x` int(11) default NULL,
                `cart_y` int(11) default NULL,
                `creation_date` datetime default NULL,
                `updation_date` datetime default NULL,
                `color` varchar(255) default NULL,
                `bg_color` varchar(255) default NULL,
                `font` varchar(255) default NULL,
                `font_size` varchar(255) default NULL,
                `text_status` int(11) default NULL,
                `tip` int(11) default 0,
                `tip_pos` int(11) default 0,
                `tip_width` int(11) default 180,
                `tip_color` varchar(255) NOT NULL,
                `tip_bg` varchar(255) NOT NULL,
                `expiry_date` datetime default NULL,
                `start_date` datetime default NULL,
                `url` varchar(255) NOT NULL,
                `y_coordinate_listing` int(11) default NULL,
                `y_coordinate_product` int(11) default NULL,
                `product` int(11) default 0,
                `listing` int(11) default 0,
                PRIMARY KEY  (`sticker_id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);

        $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_products';
        Db::getInstance()->execute($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'fmm_stickers_products(
                    `sticker_id` int(11) NOT NULL,
                    `id_product` int(11) NOT NULL,
                    PRIMARY KEY  (`sticker_id`,`id_product`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);

        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickers_lang` (
                    `sticker_id` int(10) NOT NULL,
                    `id_lang` int(10) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `tip_txt` text,
                    PRIMARY KEY (`sticker_id`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickersbanners` (
                    `stickersbanners_id` int(11) NOT NULL auto_increment,
                    `color` varchar(255) default NULL,
                    `bg_color` varchar(255) default NULL,
                    `font` varchar(255) default NULL,
                    `font_size` varchar(255) default NULL,
                    `font_weight` varchar(255) default NULL,
                    `border_color` varchar(255) default NULL,
                    `start_date` datetime default NULL,
                    `expiry_date` datetime default NULL,
                    PRIMARY KEY (`stickersbanners_id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickersbanners_lang` (
                    `stickersbanners_id` int(10) NOT NULL,
                    `id_lang` int(10) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    PRIMARY KEY (`stickersbanners_id`, `id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        // multishop stickers
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickers_shop` (
                    `sticker_id` int(10) NOT NULL,
                    `id_shop` int(10) NOT NULL,
                    PRIMARY KEY (`sticker_id`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        // multishop banners
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickersbanners_shop` (
                    `stickersbanners_id` int(10) NOT NULL,
                    `id_shop` int(10) NOT NULL,
                    PRIMARY KEY (`stickersbanners_id`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');

        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickersbanners_products` (
                    `stickersbanners_id` int(10) NOT NULL,
                    `id_product` int(10) NOT NULL,
                    PRIMARY KEY (`stickersbanners_id`, `id_product`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickers_rules` (
                    `fmm_stickers_rules_id` int(11) NOT NULL auto_increment,
                    `sticker_id` int(10) NOT NULL,
                    `title` varchar(255) default NULL,
                    `rule_type` varchar(255) default NULL,
                    `value` varchar(255) default NULL,
                    `status` int(10) unsigned NOT NULL,
                    `start_date` datetime default NULL,
                    `expiry_date` datetime default NULL,
                    `excluded_p` varchar(255) default NULL,
                    PRIMARY KEY (`fmm_stickers_rules_id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fmm_stickers_rules_shop` (
                    `fmm_stickers_rules_id` int(10) NOT NULL,
                    `id_shop` int(10) NOT NULL,
                    PRIMARY KEY (`fmm_stickers_rules_id`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        return true;
    }

    private function uninstallDb()
    {
        // Delete Tables
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_products');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickersbanners_shop');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_lang');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickersbanners');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickersbanners_lang');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_shop');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickersbanners_products');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_rules');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'fmm_stickers_rules_shop');
        return true;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/stickers.css');
    }
    
    public function hookdisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
    }

    public function getContent()
    {
        $this->_html = $this->display(__FILE__, 'views/templates/hook/info.tpl');
        $helper = $this->configForm();
        $this->postProcess();
        $helper->fields_value['sticker_type[][sticker_type]'] = Configuration::get('sticker_type_val');
        $this->html = '';
        if (Tools::isSubmit('error'.$this->name)) {
            $this->html .= '<div class="conf alert alert-warning">'.$this->l('New settings not saved (visible items must be positive integer)').'</div>';
        }
        if (Tools::isSubmit('success'.$this->name)) {
            $this->html .= '<div class="conf alert alert-success">'.$this->l('Settings saved successful').'</div>';
        }
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '<') == true) {
            $warning = $this->context->controller->warnings[] = $this->l('1. Please see the menu created on Left to add/edit stickers.');
            $warning_second = $this->context->controller->warnings[] = $this->l('2. If you cannot see sticker on listing page it might be the missing hook displayProductListFunctionalButtons in your theme.');
            $warning_third = $this->context->controller->warnings[] = $this->l('3. If you are using CSS Based stickers than add this hook to your images TPL {hook h=\'displayProductPageCss\' id_product=$product.id_product}');
        } else {
            $warning = $this->displayWarning($this->l('1. Please see the menu created on Left to add/edit stickers.'));
            $warning_second = $this->displayWarning($this->l('2. If you cannot see sticker on listing page and using JS Based settings, it might be the missing hook displayProductListFunctionalButtons in your theme.'));
            $warning_third = $this->displayWarning($this->l('3. If you are using CSS Based stickers than add this hook to your images TPL {hook h=\'displayProductPageCss\' product=$product}'));
        }
        return $warning.$warning_second.$warning_third.$this->_html.$this->html.$helper->generateForm($this->fields_form);
    }

    private function postProcess()
    {
        if (Tools::isSubmit('save'.$this->name)) {
            $this->registerHook('displayProductPageCss');
            $sticker_type = Tools::getValue('sticker_type');
            $sticker_type_val = $sticker_type[0]['sticker_type'];
            Configuration::updateValue('sticker_type_val', $sticker_type_val);
            $this->registerHook('displayStickers');
        }
    }

    public function configForm()
    {
        $ps_v = (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) ? 1 : 0;
        $force_ssl = Configuration::get('PS_SSL_ENABLED');
        $base = ($force_ssl > 0) ? _PS_BASE_URL_SSL_.__PS_BASE_URI__ : _PS_BASE_URL_.__PS_BASE_URI__;
        $path_img = ($ps_v > 0) ? $base.'modules/'.$this->name.'/views/img/help.png' : $base.'modules/'.$this->name.'/views/img/help_16.png';
        $path_tpl = ($ps_v > 0) ? '/themes/YOUR_THEME/templates/catalog/_partials/miniatures/product.tpl' : '/themes/YOUR_THEME/product-list.tpl';
        $path_img_ii = ($ps_v > 0) ? $base.'modules/'.$this->name.'/views/img/help_ii.png' : $base.'modules/'.$this->name.'/views/img/help_16_ii.png';
        $path_tpl_ii = ($ps_v > 0) ? '/themes/YOUR_THEME/templates/catalog/_partials/product-cover-thumbnails.tpl' : '/themes/YOUR_THEME/product.tpl';
        $sticker_radio = array(
            array(
                'sticker_type' => 1,
                'name' => 'JavaScript Based'
                ),
            array(
                'sticker_type' => 2,
                'name' => 'CSS Based'
                ),
            );

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Stickers Settings'),
                ),
            'input' => array(
                array(
                'type' => 'select',
                'label' => $this->l('Type of Sticker:'),
                'width' => 'auto',
                'name' => 'sticker_type[][sticker_type]',
                'options' => array(
                    'query' => $sticker_radio,
                    'id' => 'sticker_type',
                    'name' => 'name',
                    )
                ),
            ),
            'description' => '<p>In case you are using CSS Based, please add this hook <b>{hook h=\'displayProductPageCss\' product=$product}</b> in two files<br/> <b>1:</b> file '.$path_tpl.'
                like shown in image: <img src="'.$path_img.'" /><br/><b>2:</b> In file: '.$path_tpl_ii.'<br/>like shown in image: <img src="'.$path_img_ii.'" /></p>',
            'submit' => array(
            'name' => 'save'.$this->name,
            'title' => $this->l('Save'),
            'class' => 'button btn btn-default pull-right',
            ),
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->context->controller->_languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $this->context->controller->default_form_language;
        $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'save'.$this->name;
        $helper->title = $this->l('Product Labels and Stickers(settings)');
        return $helper;
    }

    public function hookdisplayProductListFunctionalButtons($params)
    {
        if(isset($params['product']['id_product'])){
            $id = (int)$params['product']['id_product'];
            $id = ($id <= 0) ? Tools::getValue('id_product') : $id;
            $type = (int)Configuration::get('sticker_type_val');
            $type = ($type <= 0) ? 1 : $type;
            $this->getStickersCollection($id, 'listing');
            //Check if CSS or JS based stickers
            if ($type == 1) {
                if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
                    return $this->display(__FILE__, 'views/templates/hook/js_base/listing_17.tpl');
                } else {
                    return $this->display(__FILE__, 'views/templates/hook/js_base/listing.tpl');
                }
            }
        }
    }

    public function hookDisplayFooterProduct()
    {
        $type = (int)Configuration::get('sticker_type_val');
        $type = ($type <= 0) ? 1 : $type;
        $id = (int)Tools::getValue('id_product');
        $this->getStickersCollection($id, 'product');
        //Check if CSS or JS based stickers
        if ($type == 1) {
            if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
                return $this->display(__FILE__, 'views/templates/hook/js_base/productfooter_17.tpl');
            } else {
                return $this->display(__FILE__, 'views/templates/hook/js_base/productfooter.tpl');
            }
        }
    }
    public function hookDisplayProductPageCss($params)
    {
        
        $type = (int)Configuration::get('sticker_type_val');
        $type = ($type <= 0) ? 1 : $type;
        if (Validate::isLoadedObject($params['product'])) {
            $product_class = $params['product'];
            $id = (int)$product_class->id_product;
        } else {
            $id = (int)$params['product']['id_product'];
        }
        $id = ($id <= 0) ? Tools::getValue('id_product') : $id;
        $page_name = Dispatcher::getInstance()->getController();
        $id_category = (int)Tools::getValue('id_category');
        if ($id_category > 0 || $page_name == 'index') {
            $this->getStickersCollection($id, 'listing');
        } else {
            $this->getStickersCollection($id, 'product');
        }
        if ($type == 2) {
            if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
                return $this->display(__FILE__, 'views/templates/hook/css_base/productfooter_17.tpl');
            } else {
                return $this->display(__FILE__, 'views/templates/hook/css_base/productfooter.tpl');
            }
        }
    }
    
    public function hookDisplayProductListReviews($params)
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return $this->hookdisplayProductListFunctionalButtons($params);
        }
    }

    public function hookDisplayProductButtons($params)
    {
        $object = new Stickers();
        $id_product = (int)Tools::getValue('id_product');
        $id_product = ($id_product <= 0) ? (int)$params['id_product'] : $id_product;
        $stickers_banner = $object->getProductBanner($id_product);
        $type = (int)Configuration::get('sticker_type_val');
        $type = ($type <= 0) ? 1 : $type;
        if ($type == 2) {
            $base_image = __PS_BASE_URI__.'img/';
            $base_image = $base_image;
            $this->context->smarty->assign('module_dir', _PS_MODULE_DIR_);
            $this->context->smarty->assign('stickers_banner', $stickers_banner);
            $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            $this->context->smarty->assign(array(
                'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
                'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
                'force_ssl' => $force_ssl
            ));
            return $this->display(__FILE__, 'views/templates/hook/css_base/product_banners.tpl');
        }
    }

    public function hookDisplayCatalogListing($params)
    {
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
            $object = new Stickers();
            $pids = $object->getPids();
            foreach ($pids as $pid) {
                if ($params['product']['id_product'] == $pid['id_product']) {
                    $type = Configuration::get('sticker_type_val');
                    $type = $type;
                    $id = (int)$params['product']['id_product'];
                    $this->getStickersCollection($id);
                    return $this->display(__FILE__, 'views/templates/hook/js_base/listing15.tpl');
                }
            }
        }
    }
    
    public function hookDisplayAdminProductsExtra($params)
    {
        $obj_model = new Stickers();
        $id_product = (int)Tools::getValue('id_product');
        $id_product = ($id_product <= 0) ? (int)$params['id_product'] : $id_product;
        $fmm_stickers = $obj_model->getAllStickers();
        $fmm_banners = $obj_model->getAllBanners();
        $fmm_banners_selected = $obj_model->getSelectedBanners($id_product);
        $selected_stickers = $obj_model->getProductStickersStatic($id_product);
        $base_image = __PS_BASE_URI__.'img/';
        $product = new Product((int)$id_product, true, $this->context->language->id);
        if (isset($product->id_product_attribute)) {
            $cover = ((int)$product->id_product_attribute > 0) ? Product::getCombinationImageById((int)$product->id_product_attribute, $this->context->language->id) : Product::getCover($id_product);
        }
        else {
            $cover = Product::getCover($id_product);
        }
        
        $image_url = Context::getContext()->link->getImageLink($product->link_rewrite, $cover['id_image']);

        $this->context->smarty->assign('base_image', $base_image);
        $this->context->smarty->assign('fmm_stickers', $fmm_stickers);
        $this->context->smarty->assign('selected_stickers', $selected_stickers);
        $this->context->smarty->assign('_PS_VERSION_', _PS_VERSION_);
        $this->context->smarty->assign('img_url', $image_url);
        $this->context->smarty->assign('fmm_banners', $fmm_banners);
        $this->context->smarty->assign('banners_selected', $fmm_banners_selected);
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            return $this->display(__FILE__, 'views/templates/admin/producttab_17.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/admin/producttab.tpl');
        }
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product = ($id_product <= 0) ? (int)$params['id_product'] : $id_product;
        $selected_stic = Tools::getValue('selected_stickers');
        if (isset($selected_stic)) {
            $sticker_ids = Tools::getValue('stickerIds');
            if (isset($sticker_ids) && !empty($sticker_ids)) {
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'fmm_stickers_products`
                    WHERE `id_product` = '.(int)$id_product);

                foreach ($sticker_ids as $_stickers) {
                    Db::getInstance()->execute('
                    INSERT INTO `'._DB_PREFIX_.'fmm_stickers_products` (`id_product`, `sticker_id`)
                    VALUES ('.(int)$id_product.', '.(int)$_stickers.')');
                }
            } else {
                Db::getInstance()->execute('
                    DELETE FROM `'._DB_PREFIX_.'fmm_stickers_products`
                    WHERE `id_product` = '.(int)$id_product);
            }
        }
        $selected_banner = Tools::getValue('stickerbanner');
        if ($selected_banner <= 0) {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'fmm_stickersbanners_products`
                    WHERE `id_product` = '.(int)$id_product);
        } else {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'fmm_stickersbanners_products`
                WHERE `id_product` = '.(int)$id_product);
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'fmm_stickersbanners_products` (`id_product`, `stickersbanners_id`)
                VALUES ('.(int)$id_product.', '.(int)$selected_banner.')');
        }
    }
    
    public function hookdisplayStickers($params)
    {
        $id = (int)$params['product']['id_product'];
        $this->getStickersCollection($id);
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            return $this->display(__FILE__, 'views/templates/hook/js_base/listing_17.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/hook/js_base/listing.tpl');
        }
    }
    
    private function getStickersCollection($id, $type)
    {
        $object = new Stickers();
        $rules = new Rules();
        $product = new Product((int)$id, true, $this->context->language->id);
        $category_data = $product->getCategories();
        $stickers_pro = $object->getProductStickers($id, $type);
        $stickers_banner = $object->getProductBanner($id);
        $_price = Tools::ps_round($product->price);


        //For Stickers Rules if any matches - Tags
        $tags_exist = Tag::getProductTags((int)$id);
        $new_stickers_colllection = $rules->keyNewExists($id);
        $is_discounted = (int)$product->isDiscounted($product->id);
        $check_stock = (int)StockAvailable::getQuantityAvailableByProduct($product->id);
        $product_sales = (int)ProductSale::getNbrSales($id);
        $page_name = Dispatcher::getInstance()->getController();
        $default_attr = $product->getDefaultAttribute($product->id);
        $stock = (int)StockAvailable::getQuantityAvailableByProduct($product->id, $default_attr);

        $features = $product->getFeatures();
        
        $fmm_stickers_rules_id = array();
        $coordinate = 42;
        if (!empty($tags_exist)) {
            $stickers_colllection = $rules->keyTagExists($tags_exist);
            if (!empty($stickers_colllection)) {
                foreach ($stickers_colllection as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick, $type));
                }
            }
        }
        //Check for reference match
        if (!empty($product->reference)) {
            $stickers_colllection = $rules->keyRefExists($product->reference);
            if (!empty($stickers_colllection)) {
                foreach ($stickers_colllection as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick, $type));
                }
            }
        }
        //Check for Price match
        if ($_price > 0) {
            $stickers_colllection = $rules->keyPriceExists($_price, $id);
            if (!empty($stickers_colllection)) {
                foreach ($stickers_colllection as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick, $type));
                }
            }
            $_stickers_colllection = $rules->keyPriceGreaterExists($_price, $id);

            if (!empty($_stickers_colllection)) {
                foreach ($_stickers_colllection as $_stick) {
                    array_push($stickers_pro, $object->getSticker($_stick, $type));
                }
            }
        }
        //Check for new Products match

        if (!empty($new_stickers_colllection) && (int)$product->new > 0) {
            foreach ($new_stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        //Check for Discounted Product rules
        if ($is_discounted > 0) {
            $stickers_colllection = $rules->keySaleExists($id);

            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        //Finally check for category rule existance
        $rule_category = $rules->getAllApplicable('category');
        
        foreach ($rule_category as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);

            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            if ($inarr) {
                unset($rule_category[$key]);
            }
        }
        
        if (count($rule_category) > 0) {
            $category_applicable = array();
            foreach ($category_data as $key) {
                $return = $rules->getIsCategoryStickerApplicable($key, $id);
                if (!empty($return)) {
                    foreach ($return as $key => $value) {
                        array_push($category_applicable, $value);
                    }
                }
            }

            if (count($category_applicable) > 0) {
                foreach ($category_applicable as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick, $type));
                }
            }
        }

        $rule_feature = $rules->getAllApplicable('p_feature');

        foreach ($rule_feature as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            if ($inarr) {
                unset($rule_feature[$key]);
            }
        }

        if (count($rule_feature) > 0) {
            $stickers_colllection = $rules->keyFeatureExists($rule_feature, $features);
            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        
        //Now check for brands rule existance
        $rule_brands = $rules->getAllApplicable('brand');
        foreach ($rule_brands as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            if ($inarr) {
                unset($rule_brands[$key]);
            }
        }

        if (count($rule_brands) > 0) {
            $stickers_colllection = $rules->keyBrandsExists($rule_brands, (int)$product->id_manufacturer);
            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }

        $rule_conditions = $rules->getAllApplicable('condition');
        foreach ($rule_conditions as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);

            if ($inarr) {
                unset($rule_conditions[$key]);
            }
        }
        if (count($rule_conditions) > 0) {
            $stickers_colllection = $rules->keyConditionExists($rule_conditions, $product->condition);
            
            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        
        $rule_p_type = $rules->getAllApplicable('p_type');
        foreach ($rule_p_type as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            
            if ($inarr) {
                unset($rule_p_type[$key]);
            }
        }
        
        if (count($rule_p_type) > 0) {
            $stickers_colllection = $rules->keyTypeExists($rule_p_type, $product);
            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        //Now check for supplier rule existance
        $rule_supplier = $rules->getAllApplicable('supplier');
        foreach ($rule_supplier as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            
            if ($inarr) {
                unset($rule_supplier[$key]);
            }
        }

        if (count($rule_supplier) > 0 && (int)$product->id_supplier > 0) {
            $stickers_colllection = $rules->keySupplierExists($rule_supplier, (int)$product->id_supplier);
            foreach ($stickers_colllection as $stick) {
                array_push($stickers_pro, $object->getSticker($stick, $type));
            }
        }
        //Check for product rules if any
        $rule_products = $rules->getAllApplicable('product');
        foreach ($rule_products as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            
            if ($inarr) {
                unset($rule_products[$key]);
            }
        }
        if (count($rule_products) > 0) {
            $stickers_colllection = $rules->keyProductsExists($rule_products, (int)$id);
            if (count($stickers_colllection)) {
                foreach ($stickers_colllection as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick, $type));
                }
            }
        }
        //Check for Bestseller rule
        if ($product_sales > 0) {
            $rule_bestseller = $rules->getAllApplicable('bestseller');

            foreach ($rule_bestseller as $key => $value) {
                $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
                $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
                $excluded_p = explode(',', $excluded_p);

                $inarr = in_array($id, $excluded_p);
                
                if ($inarr) {
                    unset($rule_bestseller[$key]);
                }
            }

            if (count($rule_bestseller) > 0) {
                foreach ($rule_bestseller as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick['sticker_id'], $type));
                }
            }
        }

        //Check for Out of Stock status
        if ($check_stock <= 0) {
            $rule_oos = $rules->getAllApplicable('outofstock');
            foreach ($rule_oos as $key => $value) {
                $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
                $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
                $excluded_p = explode(',', $excluded_p);

                $inarr = in_array($id, $excluded_p);
                
                if ($inarr) {
                    unset($rule_oos[$key]);
                }
            }
            if (count($rule_oos) > 0) {
                foreach ($rule_oos as $stick) {
                    array_push($stickers_pro, $object->getSticker($stick['sticker_id'], $type));
                }
            }
        }
        //Customer groups status
        $rule_groups = $rules->getAllApplicable('customer');
        foreach ($rule_groups as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);

            if ($inarr) {
                unset($rule_groups[$key]);
            }
        }

        if (count($rule_groups) > 0) {
            $id_customer = (int)$this->context->customer->id;
            $groups = Customer::getGroupsStatic($id_customer);
            //$id_guest = (int)$this->context->cookie->id_guest;
            foreach ($rule_groups as $group) {
                $valid_groups = explode(',', $group['value']);
                //check for Visitor/Guest group first
                if ($id_customer <= 0) {
                    if (in_array('1', $valid_groups) || in_array('2', $valid_groups)) {
                        array_push($stickers_pro, $object->getSticker($group['sticker_id'], $type));
                    }
                } elseif ($id_customer > 0) {//check for logged in groups
                    $result = array_intersect($groups, $valid_groups);
                    if (is_array($result) && !empty($result)) {
                        array_push($stickers_pro, $object->getSticker($group['sticker_id'], $type));
                    }
                }
            }
        }
        //Check for stock if greater than X
        $rule_stock_greater = $rules->getAllApplicable('stock_g');
        
        foreach ($rule_stock_greater as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            
            if ($inarr) {
                unset($rule_stock_greater[$key]);
            }
        }

        if (count($rule_stock_greater) > 0) {
            foreach ($rule_stock_greater as $stick) {
                if ($stock > $stick['value']) {
                    array_push($stickers_pro, $object->getSticker($stick['sticker_id'], $type));
                }
            }
        }
        //Check for stock if less than X
        $rule_stock_lesser = $rules->getAllApplicable('stock_l');
        
        foreach ($rule_stock_lesser as $key => $value) {
            $fmm_stickers_rules_id[] = $value['fmm_stickers_rules_id'];
            $excluded_p = Rules::getStickerData($value['fmm_stickers_rules_id']);
            $excluded_p = explode(',', $excluded_p);

            $inarr = in_array($id, $excluded_p);
            
            if ($inarr) {
                unset($rule_stock_lesser[$key]);
            }
        }
        if (count($rule_stock_lesser) > 0) {
            foreach ($rule_stock_lesser as $stick) {
                if ($stock < $stick['value']) {
                    array_push($stickers_pro, $object->getSticker($stick['sticker_id'], $type));
                }
            }
        }
        $new_array = array();
        foreach ($stickers_pro as $key => $value) {
            $id_sticker = $value['sticker_id'];
            if (!$id_sticker || $id_sticker <= 0) {
                continue;
            } else {
                array_push($new_array, $value);
            }
        }
        if (!empty($new_array)) {
            foreach ($new_array as &$sticker) {
                if ($page_name == 'product' && (int)$sticker['y_coordinate_product'] > 0) {
                    $coordinate = (int)$sticker['y_coordinate_product'];
                } elseif ((int)$sticker['y_coordinate_listing'] > 0) {
                    $coordinate = (int)$sticker['y_coordinate_listing'];
                }
                $sticker['axis'] = (int)$coordinate;
                if ($page_name != 'product') {
                    $sticker['sticker_size'] = (isset($sticker['sticker_size_list']) && $sticker['sticker_size_list'])? $sticker['sticker_size_list'] : '';
                }

                // setting default values for undefined properties
                $sticker['x_align'] = (empty($sticker['x_align']))? 'right' : $sticker['x_align'];
                $sticker['y_align'] = (empty($sticker['y_align']))? 'top' : $sticker['y_align'];
                $sticker['text_status'] = (!isset($sticker['text_status']))? 0 : $sticker['text_status'];
                $sticker['color'] = (!isset($sticker['color']))? '#000' : $sticker['color'];
                $sticker['font'] = (!isset($sticker['font']))? 'Arial' : $sticker['font'];
                $sticker['font_size'] = (!isset($sticker['font_size']))? 14 : $sticker['font_size'];
                $sticker['tip'] = (!isset($sticker['tip']))? 0 : $sticker['tip'];
                $sticker['sticker_opacity'] = (!isset($sticker['sticker_opacity']))? 0 : $sticker['sticker_opacity'];
                $sticker['sticker_size_list'] = (!isset($sticker['sticker_size_list']))? '' : $sticker['sticker_size_list'];
            }
        }


        $base_image = __PS_BASE_URI__.'img/';
        $position = Configuration::get('sticker_pos');
        $size = Configuration::get('sticker_size');
        $opacity = Configuration::get('sticker_opacity');
        $this->context->smarty->assign('base_image', $base_image);
        $this->context->smarty->assign('size', $size);
        $this->context->smarty->assign('opacity', $opacity);
        $this->context->smarty->assign('position', $position);
        $this->context->smarty->assign('id', $id);
        $this->context->smarty->assign('stickers', $new_array);
        $this->context->smarty->assign('module_dir', _PS_MODULE_DIR_);
        $this->context->smarty->assign('stickers_banner', $stickers_banner);
        $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $this->context->smarty->assign(array(
            'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
            'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
            'force_ssl' => $force_ssl
        ));
    }
}
