<?php
/**
 * 2007-2021 PrestaShop
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2021 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminRelatedProductsRulesController extends ModuleAdminController
{
    public $asso_type = 'shop';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'related_products_rules';
        $this->className = 'RelatedProductsRules';
        $this->lang = true;
        $this->identifier = 'id_related_products_rules';
        parent::__construct();
        $this->context = Context::getContext();

        $this->fields_list = array(
            'id_related_products_rules' => array(
                'title' => $this->l('ID'),
                'width' => 120,
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'titles' => array(
                'title' => $this->l('Title'),
                'width' => 100,
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'date_from' => array(
                'title' => $this->l('From'),
                'align' => 'center',
                'width' => 100,
                'type' => 'date',
                'search' => true,
                'orderby' => false,
            ),
            'date_to' => array(
                'title' => $this->l('To'),
                'align' => 'center',
                'width' => 100,
                'type' => 'date',
                'search' => true,
                'orderby' => false,
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'active' => 'active',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
            ),
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => 'Delete selected',
                'confirm' => 'Delete selected items?',
                'icon' => 'icon-trash',
            ),
        );
    }

    public function renderForm()
    {
        $ps_version = (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) ? 1 : 0;
        $back = Tools::safeOutput(Tools::getValue('back', ''));
        $btn_title = $this->l('Save');
        $form_title = (($id_related_products_rules = (int) Tools::getValue('id_related_products_rules')) == 0) ?
        $this->l('Set New Related Products Rule')
        : $this->l('Edit Related Products Rule');
        if (empty($back)) {
            $back = self::$currentIndex . '&token=' . $this->token;
        }

        if ($id_related_products_rules) {
            $btn_title = $this->l('Update');
            $form_title = $this->l('Edit Related Products Rule');
        }
        $groups = array(
            array("id_group" => "1", "name" => $this->l('Best sales products')),
            array("id_group" => "2", "name" => $this->l('Discounted products')),
            array("id_group" => "3", "name" => $this->l('New products')),
            array("id_group" => "4", "name" => $this->l('Top Viewed products')),
            array("id_group" => "5", "name" => $this->l('Products in the same category')),
            array("id_group" => "6", "name" => $this->l('Products in same brand')),
            array("id_group" => "7", "name" => $this->l('Products in same brand and category')),
            array("id_group" => "8", "name" => $this->l('Customers who bought this product also bought')),
            array("id_group" => "9", "name" => $this->l('Product tags')),
            array("id_group" => "10", "name" => $this->l('Product with specific category')),
            array("id_group" => "11", "name" => $this->l('Specific Product')),
        );
        $pages = array(
            array("id_page" => "1", "name" => $this->l('Home Page')),
            array("id_page" => "2", "name" => $this->l('Category Page')),
            array("id_page" => "3", "name" => $this->l('Product Detail Page')),
            array("id_page" => "4", "name" => $this->l('Cart Page')),
            array("id_page" => "5", "name" => $this->l('CMS Page'))
        );
        $pages_cms = CMS::getCMSPages($this->context->language->id, null, true, null);
        
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $form_title,
                'icon' => 'icon-list',
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                    'hint' => $this->l('Display on front office or Not'),
                ),
                array(
                    'label' => $this->l('Title'),
                    'type' => 'text',
                    'name' => 'titles',
                    'lang' => true,
                    'col' => '8',
                    //'desc' => $this->l('Only used in backoffice.'),
                ),
                array(
                    'type' => 'text',
                    'label' => '',
                    'name' => 'page',
                ),
                array(
                    'type' => 'text',
                    'label' => '',
                    'name' => 'pages_cms',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Select Rule'),
                    'name' => 'group',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Enter Product Tags'),
                    'name' => 'tagsproduct',
                    'col' => '8',
                ),
                array(
                    'type' => 'text',
                    'label' => '',
                    'name' => 'specific_cat',
                    'col' => '8',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Search Product'),
                    'name' => 'specific_pro',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('No of Products'),
                    'col' => '3',
                    'name' => 'no_pro',
                    'id' => 'no_pro',
                    'desc' => $this->l('Please enter 0 for all products(rule)'),
                ),
                array(
                    'label' => $this->l('From(Optional)'),
                    'type' => 'date',
                    'value' => true,
                    'name' => 'date_from',
                    'desc' => $this->l('Please Select Start Date'),
                    'required' => false,
                ),
                array(
                    'label' => $this->l('To(Optional)'),
                    'type' => 'date',
                    'value' => true,
                    'name' => 'date_to',
                    'desc' => $this->l('Please Select End Date'),
                    'required' => false,
                ),
            ),
        );
        $this->fields_form['submit'] = array(
            'title' => $btn_title,
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                'col' => '6',
            );
        }
        $id_related_products_rules = (int) Tools::getValue('id_related_products_rules');
        $sql = new DbQuery();
        $sql->select('id_rules, no_products, id_page, id_cms_pages');
        $sql->from('related_products_rules', 'rpml');
        $sql->where('rpml.id_related_products_rules =' . $id_related_products_rules);
        $selectedRules = Db::getInstance()->getRow($sql);
        $sql = new DbQuery();
        $sql->select('tags_value');
        $sql->from('related_products_rules', 'rpl');
        $sql->where('rpl.id_related_products_rules =' . $id_related_products_rules);
        $tags_value = Db::getInstance()->getValue($sql);
        $category_tree = '';
        $field_values = $this->getConfigurationValues($id_related_products_rules);
        $root = Category::getRootCategory();
        $categories = Category::getSimpleCategories($this->context->language->id);
        $tree = new HelperTreeCategories('associated-categories-tree', $this->l('Select Slider Categories'));
        $tree->setRootCategory($root->id);
        $tree->setUseCheckBox(true);
        $tree->setUseSearch(true);
        if (!empty($field_values) && !empty($field_values['category'])) {
            $selected_categories = explode(',', $field_values['category']);
            $tree->setSelectedCategories($selected_categories);
        }
        $category_tree = $tree->render();
        $products = array();
        $products = $this->getProductsCollection($id_related_products_rules);
        if ($products != null) {
            $products = explode(',', $products);
            if (!empty($products) && is_array($products)) {
                foreach ($products as &$product) {
                    $product = new Product(
                        (int) $product,
                        true,
                        (int) $this->context->language->id
                    );
                    $product->id_product_attribute = (int) Product::getDefaultAttribute(
                        $product->id
                    ) > 0 ? (int) Product::getDefaultAttribute($product->id) : 0;
                    $_cover = ((int) $product->id_product_attribute > 0) ? Product::getCombinationImageById(
                        (int) $product->id_product_attribute,
                        $this->context->language->id
                    ) : Product::getCover($product->id);
                    if (!is_array($_cover)) {
                        $_cover = Product::getCover($product->id);
                    }
                    $product->id_image = $_cover['id_image'];
                }
            }
        } else {
            $products = '';
        }
        if (!empty($selectedRules)) {
            $this->fields_value['no_pro'] = $selectedRules['no_products'];
        }
 
        $url = $this->context->link->getAdminLink('AdminRelatedProductsRules', true);
        $ps_17 = (Tools::version_compare(_PS_VERSION_, '1.7', '>=') == true) ? 1 : 0;
        $this->context->smarty->assign('ps_version', (int) $ps_version);
        $this->context->smarty->assign('rules', $groups);
        $this->context->smarty->assign('pages', $pages);
        $this->context->smarty->assign('pages_cms', $pages_cms);
        $this->context->smarty->assign('selectedRules', $selectedRules);
        $this->context->smarty->assign('tags_value', $tags_value);
        $this->context->smarty->assign('category_tree', $category_tree);
        $this->context->smarty->assign('ps_17', $ps_17);
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('categories', $categories);
        $this->context->smarty->assign('action_url', $url .
            '&action=getSearchProducts&forceJson=1&disableCombination=1&exclude_packs=0&excludeVirtuals=0&limit=20');
        return parent::renderForm();
    }

    public static function getProductsCollection($id_related_products_rules)
    {
        $sql = new DbQuery();
        $sql->select('related_products_list');
        $sql->from('related_products_rules', 'rpl');
        $sql->where('rpl.id_related_products_rules =' . $id_related_products_rules);
        $list = Db::getInstance()->getValue($sql);

        return $list;
    }

    public function getConfigurationValues($id_related_products_rules)
    {
        $sql = new DbQuery();
        $sql->select('category_box');
        $sql->from('related_products_rules', 'rpl');
        $sql->where('rpl.id_related_products_rules =' . $id_related_products_rules);
        $category_tree = Db::getInstance()->getValue($sql);
        $conf_values = array(
            'category' => $category_tree,
        );
        return $conf_values;
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_zone'] = array(
                'href' => self::$currentIndex . '&addrelated_products_rules&token=' .
                $this->token,
                'desc' => $this->l('Add new Related Products Rule', null, null, false),
                'icon' => 'process-icon-new',
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function displayAllCategories()
    {
        $categories = Category::getSimpleCategories($this->context->language->id);
        return $categories;
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->context->controller->addJs(
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js'
        );
        $this->context->controller->addCss(
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css'
        );
    }

    public function postProcess()
    {
        $action = Tools::getValue('action');
        if ($action == 'getSearchProducts') {
            RelatedProductsRules::getSearchProducts();
            die();
        }
        $c_index = $this->context->link->getAdminLink('AdminRelatedProductsRules');
        if (Tools::isSubmit('submitAddrelated_products_rules')) {
            $id_related_products_rules = (int) Tools::getValue('id_related_products_rules');
            $shops = Tools::getValue('checkBoxShopAsso_related_products_rules');
            if ($id_related_products_rules) {
                $relatedproductrules = new RelatedProductsRules($id_related_products_rules);
            } else {
                $relatedproductrules = new RelatedProductsRules();
            }

            $languages = Language::getLanguages(false);
            $date_from = pSQL(Tools::getValue('date_from'));
            $date_to = pSQL(Tools::getValue('date_to'));
            $active = (int) Tools::getValue('active');
            $select = Tools::getValue('groupBox');
            $id_page = (int) Tools::getValue('pageBox');
            $no_products = Tools::getValue('no_pro');
            $tags_value = pSQL(Tools::getValue('tagsvalue'));
            $category_box = '';
            
            $id_cms_pages = (Tools::getValue('cms_pageBox'))? implode(',', Tools::getValue('cms_pageBox')) : 0;
            //$cat_pro_opt = '';
            $category_box = (Tools::getValue('categoryBox')) ? implode(
                ',',
                Tools::getValue('categoryBox')
            ) : '';
            $related_products_list = '';
            $related_products_list = (Tools::getValue('related_products')) ? implode(
                ',',
                Tools::getValue('related_products')
            ) : '';
            if ($date_from > $date_to) {
                $this->context->controller->errors[] = $this->l('Please enter the correct date ');
            }
            if (empty($select)) {
                $this->context->controller->errors[] = $this->l('Please Select the rule');
            }
            if (empty($id_page)) {
                $this->context->controller->errors[] = $this->l('Please Select Page for related Products');
            }
            if (!$this->context->controller->errors) {
                //$relatedproductrules->title = $title;
                $relatedproductrules->date_from = $date_from;
                $relatedproductrules->date_to = $date_to;
                $relatedproductrules->active = $active;
                $relatedproductrules->tags_value = $tags_value;
                $relatedproductrules->id_rules = $select;
                $relatedproductrules->id_page = $id_page;
                $relatedproductrules->id_cms_pages = $id_cms_pages;
                $relatedproductrules->no_products = $no_products;
                $relatedproductrules->category_box = $category_box;
                $relatedproductrules->related_products_list = $related_products_list;
                if ($relatedproductrules->save()) {
                    Db::getInstance()->delete(
                        'related_products_rules_lang',
                        'id_related_products_rules=' . (int) $relatedproductrules->id
                    );
                    foreach ($languages as $language) {
                        RelatedProductsRules::insertLangData(
                            (int) $relatedproductrules->id,
                            (int) $language['id_lang'],
                            pSQL(Tools::getValue('titles_' . $language['id_lang']))
                        );
                    }
                    if ($shops) {
                        Db::getInstance()->delete(
                            'related_products_rules_shop',
                            'id_related_products_rules=' . (int) $relatedproductrules->id
                        );
                        foreach ($shops as $value) {
                            Db::getInstance()->insert('related_products_rules_shop', array(
                                'id_related_products_rules' => (int) $relatedproductrules->id,
                                'id_shop' => (int) $value,
                            ));
                        }
                    } else {
                        Db::getInstance()->delete(
                            'related_products_rules_shop',
                            'id_related_products_rules=' . (int) $relatedproductrules->id
                        );
                        Db::getInstance()->insert('related_products_rules_shop', array(
                            'id_related_products_rules' => (int) $relatedproductrules->id,
                            'id_shop' => (int) $this->context->shop->id,
                        ));
                    }

                    Tools::redirectAdmin($c_index . '&conf=4');
                } else {
                    $this->errors[] = $this->l('Operation failed');
                }
            } else {
                $this->errors[] = $this->l('Operation failed');
            }
        }

        if (Tools::isSubmit('activerelated_products_rules')) {
            $id_related_products_rules = (int) Tools::getValue(
                'id_related_products_rules'
            );
            if ($id_related_products_rules) {
                $relatedproductrules = new RelatedProductsRules(
                    $id_related_products_rules
                );
                $relatedproductrules->active = !(int) $relatedproductrules->active;
                if ($relatedproductrules->update()) {
                    Tools::redirectAdmin($c_index . '&conf=4');
                } else {
                    $this->errors[] = $this->l('operation failed');
                }
            }
        }

        if (Tools::isSubmit('deleterelated_products_rules')) {
            $id_related_products_rules = (int) Tools::getValue(
                'id_related_products_rules'
            );
            Db::getInstance()->delete(
                'related_products_rules',
                'id_related_products_rules=' . (int) $id_related_products_rules
            );
            Tools::redirectAdmin(self::$currentIndex . '&conf=6&token=' . $this->token);
        }
        parent::initProcess();
        parent::postProcess();
    }
}
