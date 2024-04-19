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

class AdminRelatedProductsVisibilityController extends ModuleAdminController
{
    public $asso_type = 'shop';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'related_products_visibility';
        $this->className = 'RelatedProductVisibility';
        $this->identifier = 'id_related_products_visibility';
        $this->context = Context::getContext();
        parent::__construct();
        $this->fields_list = array(
            'id_related_products_visibility' => array(
                'title' => $this->l('ID'),
                'width' => 120,
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'width' => 100,
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'search' => false,
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
        $obj = new RelatedProductVisibility();
        $rules = $obj->selectRelatedProducts();
        $ps_version = (Tools::version_compare(
            _PS_VERSION_,
            '1.7',
            '>='
        ) == true) ? 1 : 0;
        $back = Tools::safeOutput(Tools::getValue('back', ''));
        $btn_title = $this->l('Save');
        $form_title = (($id_related_products_visibility = (int) Tools::getValue(
            'id_related_products_visibility'
        )) == 0) ?
        $this->l('Set New Visibility Criteria') : $this->l('Edit Visibility Criteria');
        if (empty($back)) {
            $back = self::$currentIndex . '&token=' . $this->token;
        }
        if ($id_related_products_visibility) {
            $btn_title = $this->l('Update');
            $form_title = $this->l('Edit Visibility Criteria');
        }
        $visibility_criteria = array(
            array("id_criteria" => "1", "name" => $this->l('Selected Categories')),
            array("id_criteria" => "2", "name" => $this->l('Specific Products')),
        );

        $this->fields_form = array(
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
                ),
                array(
                    'label' => $this->l('Title'),
                    'type' => 'text',
                    'name' => 'title',
                    'col' => '4',
                    'desc' => $this->l('Only used in backoffice.'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'rules',
                    'col' => '8',
                    'label' => $this->l('Select Rules'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'criteria',
                    'col' => '8',
                    'label' => $this->l('Select visibility criteria (Where to display)'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'specific_cat',
                    'col' => '8',
                    'label' => '',
                ),
                array(
                    'type' => 'text',
                    'name' => 'specific_prod',
                    'label' => $this->l('Search Product'),
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
        $category_tree = '';
        $field_values = $this->getConfigurationValues($id_related_products_visibility);
        $root = Category::getRootCategory();
        $categories = Category::getSimpleCategories($this->context->language->id);
        $tree = new HelperTreeCategories(
            'associated-categories-tree',
            $this->l('Select Categories')
        );
        $tree->setRootCategory($root->id);
        $tree->setUseCheckBox(true);
        $tree->setUseSearch(true);
        if (!empty($field_values) && !empty($field_values['category'])) {
            $selected_categories = explode(',', $field_values['category']);
            $tree->setSelectedCategories($selected_categories);
        }
        $category_tree = $tree->render();

        if ($id_related_products_visibility) {
            $data = new RelatedProductVisibility($id_related_products_visibility);
            $this->context->smarty->assign(
                array(
                    'selected_rules' => $data->id_related_products_rules,
                    'selected_criteria' => $data->selected_opt,
                )
            );
        }
        $products = array();
        $products = $this->getProductsCollection($id_related_products_visibility);
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

        $url = $this->context->link->getAdminLink('AdminRelatedProductsVisibility', true);
        $this->context->smarty->assign(
            'action_url',
            $url .
            '&action=getSearchProducts&forceJson=1&disableCombination=1&exclude_packs=0&excludeVirtuals=0&limit=20'
        );
        $this->context->smarty->assign(
            array(
                'rules' => $rules,
                'criteria' => $visibility_criteria,
                'ps_version' => $ps_version,
                'category_tree' => $category_tree,
                'categories' => $categories,
                'products' => $products,
            )
        );

        return parent::renderForm();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function postProcess()
    {
        $action = Tools::getValue('action');
        if ($action == 'getSearchProducts') {
            RelatedProductsRules::getSearchProducts();
            die();
        }
        $url = $this->context->link->getAdminLink('AdminRelatedProductsVisibility');
        if (Tools::isSubmit('submitAddrelated_products_visibility')) {
            $id = (int) Tools::getValue('id_related_products_visibility');
            $shops = Tools::getValue('checkBoxShopAsso_related_products_visibility');
            if ($id) {
                $obj = new RelatedProductVisibility($id);
            } else {
                $obj = new RelatedProductVisibility();
            }
            $id_rules = (int) Tools::getValue('groupBox');
            $title = pSQL(Tools::getValue('title'));
            $active = (int) Tools::getValue('active');
            $selected_opt = (int) Tools::getValue('criteria');
            $category_box = '';
            $category_box = (Tools::getValue('categoryBox')) ? implode(
                ',',
                Tools::getValue('categoryBox')
            ) : '';
            $supplier_box = (Tools::getValue('suppliers')) ? implode(
                ',',
                Tools::getValue('suppliers')
            ) : '';
            $brand_box = (Tools::getValue('brands')) ? implode(
                ',',
                Tools::getValue('brands')
            ) : '';

            $related_products_list = '';
            $related_products_list = (Tools::getValue('related_products')) ? implode(
                ',',
                Tools::getValue('related_products')
            ) : '';
            if (empty($id_rules)) {
                $this->context->controller->errors[] = $this->l(
                    'Please select rule for product that you want to show'
                );
            }
            if (empty($selected_opt)) {
                $this->context->controller->errors[] = $this->l(
                    'Please Select option where to display related products'
                );
            }
            if (!$this->context->controller->errors) {
                $obj->id_related_products_rules = $id_rules;
                $obj->title = $title;
                $obj->selected_opt = $selected_opt;
                $obj->selected_cat = $category_box;
                $obj->selected_supplier = $supplier_box;
                $obj->selected_brand = $brand_box;

                $obj->selected_prod = $related_products_list;
                $obj->active = $active;
                if ($obj->save()) {
                    if ($shops) {
                        Db::getInstance()->delete(
                            'related_products_visibility_shop',
                            'id_related_products_visibility=' . (int) $obj->id
                        );
                        foreach ($shops as $value) {
                            Db::getInstance()->insert('related_products_visibility_shop', array(
                                'id_related_products_visibility' => (int) $obj->id,
                                'id_shop' => (int) $value,
                            ));
                        }
                    } else {
                        Db::getInstance()->delete(
                            'related_products_visibility_shop',
                            'id_related_products_visibility=' . (int) $obj->id
                        );
                        Db::getInstance()->insert('related_products_visibility_shop', array(
                            'id_related_products_visibility' => (int) $obj->id,
                            'id_shop' => (int) $this->context->shop->id,
                        ));
                    }
                    Tools::redirectAdmin($url . '&conf=4');
                } else {
                    $this->errors[] = $this->l('Operation failed');
                }
            }
        }

        if (Tools::isSubmit('statusrelated_products_visibility')) {
            $id_related_products_visibility = (int) Tools::getValue(
                'id_related_products_visibility'
            );
            if ($id_related_products_visibility) {
                $obj = new RelatedProductVisibility($id_related_products_visibility);
                $obj->active = !(int) $obj->active;
                if ($obj->update()) {
                    Tools::redirectAdmin($url . '&conf=4');
                } else {
                    $this->errors[] = $this->l('operation failed');
                }
            }
        }

        if (Tools::isSubmit('deleterelated_products_visibility')) {
            $id_related_products_visibility = (int) Tools::getValue('id_related_products_visibility');
            Db::getInstance()->delete(
                'related_products_visibility_shop',
                'id_related_products_visibility=' . (int) $id_related_products_visibility
            );
            Db::getInstance()->delete(
                'related_products_visibility',
                'id_related_products_visibility=' . (int) $id_related_products_visibility
            );
            Tools::redirectAdmin(self::$currentIndex . '&conf=6&token=' . $this->token);
        }
    }

    public function getConfigurationValues($id_related_products_visibility)
    {
        $sql = new DbQuery();
        $sql->select('selected_cat');
        $sql->from('related_products_visibility', 'rpl');
        $sql->where('rpl.id_related_products_visibility =' . $id_related_products_visibility);
        $category_tree = Db::getInstance()->getValue($sql);
        $conf_values = array(
            'category' => $category_tree,
        );
        return $conf_values;
    }

    public static function getProductsCollection($id_related_products_visibility)
    {
        $sql = new DbQuery();
        $sql->select('selected_prod');
        $sql->from('related_products_visibility', 'rpl');
        $sql->where('rpl.id_related_products_visibility =' . $id_related_products_visibility);
        $list = Db::getInstance()->getValue($sql);

        return $list;
    }
}
