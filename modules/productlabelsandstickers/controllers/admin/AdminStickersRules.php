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

class AdminStickersRulesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'Rules';
        $this->table = 'fmm_stickers_rules';
        $this->identifier = 'fmm_stickers_rules_id';
        $this->lang = false;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bootstrap = true;
        parent::__construct();
        $this->context = Context::getContext();

        $this->fields_list = array(
            'fmm_stickers_rules_id' => array(
                'title' => 'ID',
                'width' => 25,
            ),
            'title' => array(
                'title' => $this->module->l('Title'),
                'width' => 'auto',
            ),
            'sticker_id' => array(
                'title' => $this->module->l('ID Sticker'),
                'width' => 25,
            ),
            'rule_type' => array(
                'title' => $this->module->l('Type'),
                'width' => 'auto',
            ),
            'status' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'type' => 'bool',
                'callback' => 'getStatus',
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function renderForm()
    {
        $sticker_class = new Stickers;
        $id_lang = (int) $this->context->language->id;
        $ps_17 = (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) ? 1 : 0;
        $edit_data = array('status' => 0,
            'sticker_id' => 0,
            'rule_type' => '',
            'value' => '',
            'start_date' => '',
            'expiry_date' => '');
        $shop_data = array();
        $shops = Shop::getShops(true, null, false);
        $id = (int) Tools::getValue('fmm_stickers_rules_id');
        $sticker_collection = $sticker_class->getAllStickers();
        if (!empty($sticker_collection)) {
            foreach ($sticker_collection as &$sticker) {
                $sticker['title'] = $sticker_class->getFieldTitle($sticker['sticker_id'], $id_lang);
            }
        }
        $products = array();
        if ($id > 0) {
            $obj = new Rules;
            $edit_data = $obj->getAllEditData($id);
            $edit_data = array_shift($edit_data);
            $edit_data['value_array'] = array();
            if (isset($edit_data['value'])) {
                $edit_data['value_array'] = explode(',', $edit_data['value']);
            }
            $shop_data = $obj->getAllEditDataShop($id);
            $products = $edit_data['value_array'];
            if (!empty($products) && is_array($products)) {
                foreach ($products as &$product) {
                    $product = new Product((int) $product, true, (int) $id_lang);
                    $product->id_product_attribute = (int) Product::getDefaultAttribute($product->id) > 0 ? (int) Product::getDefaultAttribute($product->id) : 0;
                    $_cover = ((int) $product->id_product_attribute > 0) ? Product::getCombinationImageById((int) $product->id_product_attribute, $id_lang) : Product::getCover($product->id);
                    if (!is_array($_cover)) {
                        $_cover = Product::getCover($product->id);
                    }
                    $product->id_image = $_cover['id_image'];
                }
            }
        }

        $ex_products = array();
        if ($id > 0) {
            $obj = new Rules;
            $abc = array();
            if (isset($abc)) {
                $abc = explode(',', $edit_data['excluded_p']);
            }
            $shop_data = $obj->getAllEditDataShop($id);
            $ex_products = $abc;
            if (!empty($products) && is_array($ex_products)) {
                foreach ($ex_products as &$product) {
                    $product = new Product((int) $product, true, (int) $id_lang);
                    $product->id_product_attribute = (int) Product::getDefaultAttribute($product->id) > 0 ? (int) Product::getDefaultAttribute($product->id) : 0;
                    $_cover = ((int) $product->id_product_attribute > 0) ? Product::getCombinationImageById((int) $product->id_product_attribute, $id_lang) : Product::getCover($product->id);
                    if (!is_array($_cover)) {
                        $_cover = Product::getCover($product->id);
                    }
                    $product->id_image = $_cover['id_image'];
                }
            }
        }

        $brands = ($ps_17 >= 1) ? Manufacturer::getLiteManufacturersList() : Manufacturer::getManufacturers();
        $suppliers = ($ps_17 >= 1) ? Supplier::getLiteSuppliersList() : Supplier::getSuppliers();
        if ($ps_17 <= 0 && !empty($brands)) {
            foreach ($brands as &$brand) {
                $brand['id'] = $brand['id_manufacturer'];
            }
        }
        if ($ps_17 <= 0 && !empty($suppliers)) {
            foreach ($suppliers as &$supplier) {
                $supplier['id'] = $supplier['id_supplier'];
            }
        }
        $groups = Group::getGroups($this->context->language->id);
        //dump($groups);exit;
        $categories = Category::getSimpleCategories($id_lang);
        $features = Feature::getFeatures($id_lang);
        $allfeatures = array();
        foreach ($features as $key => $value) {
            $fdetail = FeatureValue::getFeatureValuesWithLang($id_lang, $value['id_feature']);
            
            foreach ($fdetail as $kk => $vval) {
                $fdetail[$kk]['name'] = $value['name'];
            }
            foreach ($fdetail as $newk => $newval) {
                $allfeatures[] = $newval;
            }
        }

        
        //$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $url = $this->context->link->getAdminLink('AdminStickersRules', true);
        $this->context->smarty->assign(array(
            'id' => $id,
            'ps_17' => $ps_17,
            'data' => $edit_data,
            'shop_data' => $shop_data,
            'shops' => $shops,
            'action' => self::$currentIndex . '&token=' . $this->token,
            'stickers' => $sticker_collection,
            'base_image' => __PS_BASE_URI__ . 'img/',
            'pq_url' => $this->module->getPathUri(),
            'img_base' => $this->getBaseLink(),
            'brands' => $brands,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'allfeatures' => $allfeatures,
            'customers' => $groups,
            'action_url' => $url . '&action=getSearchProducts&forceJson=1&disableCombination=1&exclude_packs=0&excludeVirtuals=0&limit=20',
            'products' => $products,
            'ex_products' => $ex_products,
            //'base_admin_url' => (($force_ssl)?_PS_BASE_URL_SSL_.__PS_BASE_URI__:_PS_BASE_URL_.__PS_BASE_URI__).basename(_PS_ADMIN_DIR_),
        ));

        parent::renderForm();
        return $this->context->smarty->fetch(dirname(__FILE__) . '/../../views/templates/admin/stickers/helpers/form/rules.tpl');
    }

    public function postProcess()
    {
        $class = new Rules;
        $id = (int) Tools::getValue('fmm_stickers_rules_id');
        $id_sticker = (int) Tools::getValue('sticker_id');
        $status = Tools::getValue('status');
        $title = Tools::getValue('title');
        $shops = Tools::getValue('shops');
        $rule = Tools::getValue('rule');
        $brands = Tools::getValue('brands');
        $categories = Tools::getValue('category');
        $features = Tools::getValue('feature');
        $conditions = Tools::getValue('conditions');
        $p_types = Tools::getValue('p_types');
        $suppliers = Tools::getValue('suppliers');
        $rule_value = Tools::getValue('rule_value');
        $start_date = Tools::getValue('start_date');
        $expiry_date = Tools::getValue('expiry_date');
        $products = Tools::getValue('related_products');
        $excluded_products = Tools::getValue('excluded_products');
        if (!empty($excluded_products)) {
            $excluded_products = implode(',', $excluded_products);
        }

        $groups = Tools::getValue('customers');
        $action = Tools::getValue('action');
        if (Tools::isSubmit('submitRules')) {
            if ($id > 0) {
                if ($rule == 'brand' && !empty($brands)) {
                    $rule_value = implode(',', $brands);
                } elseif ($rule == 'supplier' && !empty($suppliers)) {
                    $rule_value = implode(',', $suppliers);
                } elseif ($rule == 'condition' && !empty($conditions)) {
                    $rule_value = implode(',', $conditions);
                } elseif ($rule == 'p_type' && !empty($p_types)) {
                    $rule_value = implode(',', $p_types);
                } elseif ($rule == 'category' && !empty($categories)) {
                    $rule_value = implode(',', $categories);

                } elseif ($rule == 'p_feature' && !empty($features)) {
                    $rule_value = implode(',', $features);
                } elseif ($rule == 'product' && !empty($products)) {
                    $rule_value = implode(',', $products);
                } elseif ($rule == 'customer' && !empty($groups)) {
                    $rule_value = implode(',', $groups);
                }

                $class->resetShops($id);
                $class->changeAll($id, $id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                $class->saveShops($id, $shops);
            } else {
                if ($id_sticker <= 0) {
                    $this->errors[] = $this->l('Please select a sticker.');
                } elseif (empty($shops)) {
                    $this->errors[] = $this->l('Please select at least one shop OR all of them.');
                } elseif (empty($rule)) {
                    $this->errors[] = $this->l('Please select a Rule.');
                } else {
                    if ($rule == 'brand') {
                        if (empty($brands)) {
                            $this->errors[] = $this->l('Please select a brand.');
                        } else {
                            $rule_value = implode(',', $brands);
                            $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                            $class->saveShops($_id, $shops);
                        }
                    } elseif ($rule == 'supplier') {
                        if (empty($suppliers)) {
                            $this->errors[] = $this->l('Please select a supplier.');
                        } else {
                            $rule_value = implode(',', $suppliers);
                            $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                            $class->saveShops($_id, $shops);
                        }
                    } elseif ($rule == 'category') {
                        if (empty($categories)) {
                            $this->errors[] = $this->l('Please select a category.');
                        } else {
                            $rule_value = implode(',', $categories);
                            $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                            $class->saveShops($_id, $shops);
                        }
                    } elseif ($rule == 'product') {
                        if (empty($products)) {
                            $this->errors[] = $this->l('Please select at least one product.');
                        } else {
                            $rule_value = implode(',', $products);
                            $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                            $class->saveShops($_id, $shops);
                        }
                    } elseif ($rule == 'customer') {
                        if (empty($groups)) {
                            $this->errors[] = $this->l('Please select at least one customer group.');
                        } else {
                            $rule_value = implode(',', $groups);
                            $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                            $class->saveShops($_id, $shops);
                        }
                    } else {
                        $_id = $class->saveAll($id_sticker, $status, $title, $rule, $rule_value, $start_date, $expiry_date, $excluded_products);
                        $class->saveShops($_id, $shops);
                    }
                }
            }
        }
        if ($action == 'getSearchProducts') {
            $this->getSearchProducts();
            die();
        }
        parent::postProcess();
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['save']);
        unset($this->toolbar_btn['cancel']);
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUI(array('ui.datepicker'));
    }

    public static function getStatus($id)
    {
        if ((int) $id <= 0) {
            $return = 'No';
        } elseif ((int) $id > 0) {
            $return = 'Yes';
        }
        return $return;
    }

    public function getBaseLink($id_shop = null, $ssl = null, $relative_protocol = false)
    {
        static $force_ssl = null;

        if ($ssl === null) {
            if ($force_ssl === null) {
                $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            }
            $ssl = $force_ssl;
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
            $shop = new Shop($id_shop);
        } else {
            $shop = Context::getContext()->shop;
        }

        if ($relative_protocol) {
            $base = '//' . ($ssl && $this->ssl_enable ? $shop->domain_ssl : $shop->domain);
        } else {
            $base = (($ssl && $this->ssl_enable) ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain);
        }

        return $base . $shop->getBaseURI();
    }

    protected function getSearchProducts()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die(json_encode($this->l('Found Nothing.')));
        }

        /*
         * In the SQL request the "q" param is used entirely to match result in database.
         * In this way if string:"(ref : #ref_pattern#)" is displayed on the return list,
         * they are no return values just because string:"(ref : #ref_pattern#)"
         * is not write in the name field of the product.
         * So the ref pattern will be cut for the search request.
         */
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }

        // Excluding downloadable products from packs because download from pack is not supported
        $forceJson = Tools::getValue('forceJson', false);
        $disableCombination = Tools::getValue('disableCombination', false);
        $excludeVirtuals = (bool) Tools::getValue('excludeVirtuals', true);
        $exclude_packs = (bool) Tools::getValue('exclude_packs', true);

        $context = Context::getContext();

        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $context->language->id . ')
                WHERE (pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\')' .
            (!empty($excludeIds) ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
            ($excludeVirtuals ? 'AND NOT EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
            ($exclude_packs ? 'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
            ' GROUP BY p.id_product';

        $items = Db::getInstance()->executeS($sql);
        if ($items && ($disableCombination || $excludeIds)) {
            $results = array();
            foreach ($items as $item) {
                if (!$forceJson) {
                    $item['name'] = str_replace('|', '&#124;', $item['name']);
                    $results[] = trim($item['name']) . (!empty($item['reference']) ? ' (ref: ' . $item['reference'] . ')' : '') . '|' . (int) $item['id_product'];
                } else {
                    $cover = Product::getCover($item['id_product']);
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => $item['name'] . (!empty($item['reference']) ? ' (ref: ' . $item['reference'] . ')' : ''),
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], (($item['id_image']) ? $item['id_image'] : $cover['id_image']), $this->getFormatedName('home'))),
                    );
                }
            }

            if (!$forceJson) {
                echo implode("\n", $results);
            } else {
                echo json_encode($results);
            }
        } elseif ($items) {
            // packs
            $results = array();
            foreach ($items as $item) {
                // check if product have combination
                if (Combination::isFeatureActive() && $item['cache_default_attribute']) {
                    $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, ag.`id_attribute_group`, pai.`id_image`, agl.`name` AS group_name, al.`name` AS attribute_name,
                                a.`id_attribute`
                            FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                            ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
                            WHERE pa.`id_product` = ' . (int) $item['id_product'] . '
                            GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                            ORDER BY pa.`id_product_attribute`';

                    $combinations = Db::getInstance()->executeS($sql);
                    if (!empty($combinations)) {
                        foreach ($combinations as $k => $combination) {
                            $k = $k;
                            $cover = Product::getCover($item['id_product']);
                            $results[$combination['id_product_attribute']]['id'] = $item['id_product'];
                            $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                            !empty($results[$combination['id_product_attribute']]['name']) ? $results[$combination['id_product_attribute']]['name'] .= ' ' . $combination['group_name'] . '-' . $combination['attribute_name']
                            : $results[$combination['id_product_attribute']]['name'] = $item['name'] . ' ' . $combination['group_name'] . '-' . $combination['attribute_name'];
                            if (!empty($combination['reference'])) {
                                $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                            } else {
                                $results[$combination['id_product_attribute']]['ref'] = !empty($item['reference']) ? $item['reference'] : '';
                            }
                            if (empty($results[$combination['id_product_attribute']]['image'])) {
                                $results[$combination['id_product_attribute']]['image'] = str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], (($combination['id_image']) ? $combination['id_image'] : $cover['id_image']), $this->getFormatedName('home')));
                            }
                        }
                    } else {
                        $results[] = array(
                            'id' => $item['id_product'],
                            'name' => $item['name'],
                            'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                            'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $this->getFormatedName('home'))),
                        );
                    }
                } else {
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => $item['name'],
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $this->getFormatedName('home'))),
                    );
                }
            }
            echo json_encode(array_values($results));
        } else {
            echo json_encode(array());
        }
    }

    public function getFormatedName($name)
    {
        $theme_name = Context::getContext()->shop->theme_name;
        $name_without_theme_name = str_replace(array('_' . $theme_name, $theme_name . '_'), '', $name);
        //check if the theme name is already in $name if yes only return $name
        if (strstr($name, $theme_name) && ImageType::getByNameNType($name, 'products')) {
            return $name;
        } elseif (ImageType::getByNameNType($name_without_theme_name . '_' . $theme_name, 'products')) {
            return $name_without_theme_name . '_' . $theme_name;
        } elseif (ImageType::getByNameNType($theme_name . '_' . $name_without_theme_name, 'products')) {
            return $theme_name . '_' . $name_without_theme_name;
        } else {
            return $name_without_theme_name . '_default';
        }
    }
}
