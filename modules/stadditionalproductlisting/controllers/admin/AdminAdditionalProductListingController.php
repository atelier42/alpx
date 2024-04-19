<?php
/**
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*/

class AdminAdditionalProductListingController extends ModuleAdminController
{
    protected $can_add = true;
    public function __construct()
    {
        $this->table = 'st_additionalproductlisting';
        $this->className = 'AdditionalProductListing';
        $this->identifier = 'id_additionalproductlisting';
        $this->lang = true;
        parent::__construct();
        $this->bootstrap = true;
        
        $types = array();
        foreach ($this->module->types as $type) {
            $types[$type['id']] = $type['name'];
        }
        $hooks = array();
        foreach ($this->module->hooks as $hook) {
            $hooks[$hook['id']] = $hook['name'];
        }

        $this->fields_list = array(
            'id_additionalproductlisting' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'callback' => 'setName',
                'align' => 'text-center',
                'lang' => true
            ),
            'filter_type' => array(
                'title' => $this->l('Product From'),
                'callback' => 'setType',
                'align' => 'text-center',
                'filter_key' => 'a!filter_type',
                'type' => 'select',
                'list' => $types
            ),
            'hook' => array(
                'title' => $this->l('Position'),
                'callback' => 'setHookValue',
                'align' => 'text-center',
                'filter_key' => 'a!hook',
                'type' => 'select',
                'list' => $hooks
            ),
            'is_carousel' => array(
                'title' => $this->l('Layout'),
                'callback' => 'setLayout',
                'align' => 'text-center',
                'filter_key' => 'a!is_carousel',
                'type' => 'select',
                'list' => array(
                    0 => $this->l('List'),
                    1 => $this->l('Carousel')
                )
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'text-center',
                'active' => 'status',
                'type' => 'bool',
            ),
        );
        
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
        
        $this->shopLinkType = 'shop';
        
        if (Shop::isFeatureActive()
            && (Shop::getContext() == Shop::CONTEXT_ALL
            || Shop::getContext() == Shop::CONTEXT_GROUP)
        ) {
            $this->can_add = false;
        }
    }
    
    public function initContent()
    {
        if (!$this->can_add && !$this->display && !$this->ajax) {
            $this->informations[] = $this->l('You have to select a shop if you want to create a new listing.');
        }

        parent::initContent();
    }
    
    public function displayDuplicateLink($token, $idAdditionalproductlisting)
    {
        $link = $this->context->link->getAdminLink('AdminAdditionalProductListing').
            '&id_additionalproductlisting='.(int)$idAdditionalproductlisting.'&duplicate';
        
        return $this->module->elementHtml(
            'duplicate_button_link',
            array(
                'name' => $this->l('Duplicate'),
                'link' => $link
            )
        );
    }
    
    public function postProcess()
    {
        if (!$this->can_add && $this->display == 'add') {
            $this->redirect_after = $this->context->link->getAdminLink('AdminAdditionalProductListing');
        }
        if (Tools::isSubmit('duplicate')) {
            if ($idAdditionalproductlisting = Tools::getvalue('id_additionalproductlisting')) {
                if (AdditionalProductListing::copy((int)$idAdditionalproductlisting)) {
                    return Tools::redirectAdmin(
                        $this->context->link->getAdminLink('AdminAdditionalProductListing').'&conf=19'
                    );
                }
            }
        }
        if (Tools::isSubmit('btnSubmit') || Tools::isSubmit('btnSaveAndStaySubmit')) {
            $idLang = (int)Configuration::get('PS_LANG_DEFAULT');
            if (
                Tools::getValue('name_'.$idLang) && Tools::strlen(Tools::getValue('name_'.$idLang)) > 190) {
                $this->errors[] = $this->l('Invalid name field value. It should be generic and not more than 56 characters long.');
            }
            if (Tools::getValue('filter_type') == 1
                && (!Tools::getValue('filter_category')
                || count(Tools::getValue('filter_category')) == 0)) {
                $this->errors[] = $this->l('Invalid category value. Please select at least one category.');
            } elseif (Tools::getValue('filter_type') == 2
                && (!Tools::getValue('filter_manufacturer')
                || count(Tools::getValue('filter_manufacturer')) == 0)) {
                $this->errors[] = $this->l('Invalid manufacturer value. Please select at least one manufacturer.');
            } elseif (Tools::getValue('filter_type') == 3
                && (!Tools::getValue('filter_supplier')
                || count(Tools::getValue('filter_supplier')) == 0)) {
                $this->errors[] = $this->l('Invalid supplier value. Please select at least one supplier.');
            } elseif (Tools::getValue('filter_type') == 4
                && (!Tools::getValue('filter_product')
                || count(Tools::getValue('filter_product')) == 0)) {
                $this->errors[] = $this->l('Invalid product value. Please choose at least one product.');
            }
            if (Tools::getValue('product_count')  && !Validate::isUnsignedId(Tools::getValue('product_count'))) {
                $this->errors[] = $this->l('Invalid product count. It should be positive numeric value.');
            }
            if (Tools::getValue('width')  && !Validate::isUnsignedId(Tools::getValue('width'))) {
                $this->errors[] = $this->l('Invalid crousel width. It should be positive numeric value.');
            }

            if ($this->errors) {
                $this->display = 'add';
                return false;
            }
        }

        return parent::postProcess();
    }

    public function processSave()
    {
        if (Tools::getValue('filter_type') == 1) {
            $_POST['filter_data'] = serialize(Tools::getValue('filter_category')) ;
        } elseif (Tools::getValue('filter_type') == 2) {
            $_POST['filter_data'] = serialize(Tools::getValue('filter_manufacturer')) ;
        } elseif (Tools::getValue('filter_type') == 3) {
            $_POST['filter_data'] = serialize(Tools::getValue('filter_supplier')) ;
        } elseif (Tools::getValue('filter_type') == 4) {
            $_POST['filter_data'] = serialize(Tools::getValue('filter_product')) ;
        }
        parent::processSave();
        if (Tools::isSubmit('btnSaveAndStaySubmit')) {
            return Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminAdditionalProductListing').'&id_additionalproductlisting='.
                (int)Tools::getvalue('id_additionalproductlisting').'&update'.$this->table.'&conf=4'
            );
        }
    }
    
    public function initToolbar()
    {
        parent::initToolbar();

        if (!$this->can_add) {
            unset($this->toolbar_btn['new']);
        }
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display) && $this->can_add) {
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add New Listing'),
                'icon' => 'process-icon-new',
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('delete');
        
        return parent::renderList();
    }

    public function renderForm()
    {
        if (!($this->loadObject(true))) {
            return;
        }

        $idLang = (int)Configuration::get('PS_LANG_DEFAULT');
        $manufacturers = Manufacturer::getManufacturers($idLang, true);
        foreach ($manufacturers as &$manufacturer) {
            $manufacturer['val'] = $manufacturer['id_manufacturer'];
        }
        $suppliers = Supplier::getSuppliers(false, $idLang, true);
        foreach ($suppliers as &$supplier) {
            $supplier['val'] = $supplier['id_supplier'];
        }

        $filter_category = array();
        $filter_product = array();
        $this->fields_value['filter_manufacturer[]'] = '';
        $this->fields_value['filter_supplier[]'] = '';

        if ($id_additionalproductlisting = Tools::getValue('id_additionalproductlisting')) {
            $ObjAdditionalProductListing = new AdditionalProductListing(
                $id_additionalproductlisting,
                $idLang
            );
            $filter_data = Tools::unSerialize($ObjAdditionalProductListing->filter_data);
            if ($ObjAdditionalProductListing->filter_type == 1) {
                $filter_category = $filter_data;
            } elseif ($ObjAdditionalProductListing->filter_type == 2) {
                if ($filter_data) {
                    foreach ($filter_data as $id_manufacturer) {
                        $this->fields_value['filter_manufacturer[]_'.$id_manufacturer] = $id_manufacturer;
                    }
                }
            } elseif ($ObjAdditionalProductListing->filter_type == 3) {
                if ($filter_data) {
                    foreach ($filter_data as $id_supplier) {
                        $this->fields_value['filter_supplier[]_'.$id_supplier] = $id_supplier;
                    }
                }
            } elseif ($ObjAdditionalProductListing->filter_type == 4) {
                $filter_product = $filter_data;
            }
        }

        $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Extra Product Listing'),
                    'icon' => 'icon-edit'
                ),
                'tabs' => array(
                    'general' => $this->l('General Inputs'),
                    'carousel' => $this->l('Carousel Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'current_tab',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'lang' => true,
                        'maxlength' => 190,
                        'desc' => $this->l('This text will be used as label and display above the product listing. Leave empty if not required.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Description'),
                        'name' => 'description',
                        'lang' => true,
                        'desc' => $this->l('This text will be used as description and display above the product list. Leave empty if not required.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Product From'),
                        'name' => 'filter_type',
                        'options' => array(
                            'query' => $this->module->types,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'class' => 'fixed-width-xxl',
                        'required' => true,
                        'hint' => $this->l('This field is required.'),
                        'desc' => $this->l('Select the filter type of the product. From where product will be display.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->l('Select Categories'),
                        'name' => 'filter_category',
                        'tree' => array(
                            'root_category' => 1,
                            'id' => 'id_category',
                            'selected_categories' => $filter_category,
                            'use_checkbox' => true,
                            'use_search' => true
                        ),
                        'required' => true,
                        'hint' => $this->l('This field is required.'),
                        'form_group_class' => 'st-filter-1 hide',
                        'desc' => $this->l('Select the specific categories from which the products will be displayed.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->l('Select Manufacturers'),
                        'name' => 'filter_manufacturer[]',
                        'values' => array(
                            'query' => $manufacturers,
                            'id' => 'id_manufacturer',
                            'val' => 'id_manufacturer',
                            'name' => 'name',

                        ),
                        'expand' => array(
                            'default' => 'show',
                            'print_total' => count($manufacturers),
                            'show' => array(
                                'icon' => 'plus-circle',
                                'text' => $this->l('Show'),
                            ),
                            'hide' => array(
                                'icon' => 'minus-circle',
                                'text' => $this->l('Hide'),
                            ),
                        ),
                        'required' => true,
                        'form_group_class' => 'st-filter-2 hide',
                        'hint' => $this->l('This field is required.'),
                        'desc' => $this->module->elementHtml(
                            'form_desc',
                            array(
                                'found' => count($manufacturers),
                                'element' => 'manufacturer',
                                'element_link' => $this->context->link->getAdminLink('AdminManufacturers')
                            )
                        ),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->l('Select Suppliers'),
                        'name' => 'filter_supplier[]',
                        'values' => array(
                            'query' => $suppliers,
                            'id' => 'id_supplier',
                            'val' => 'id_supplier',
                            'name' => 'name'
                        ),
                        'expand' => array(
                            'default' => 'show',
                            'print_total' => count($suppliers),
                            'show' => array(
                                'icon' => 'plus-circle',
                                'text' => $this->l('Show'),
                            ),
                            'hide' => array(
                                'icon' => 'minus-circle',
                                'text' => $this->l('Hide'),
                            ),
                        ),
                        'required' => true,
                        'form_group_class' => 'st-filter-3 hide',
                        'hint' => $this->l('This field is required.'),
                        'desc' => $this->module->elementHtml(
                            'form_desc',
                            array(
                                'found' => count($suppliers),
                                'element' => 'supplier',
                                'element_link' => $this->context->link->getAdminLink('AdminSuppliers')
                            )
                        ),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'html',
                        'label' => $this->l('Select Products'),
                        'name' => 'filter_product[]',
                        'required' => true,
                        'form_group_class' => 'st-filter-4 hide',
                        'html_content' => $this->getFilterProducts($filter_product, $idLang),
                        'hint' => $this->l('This field is required.'),
                        'desc' => $this->l('Select specific products to display in this position.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Position'),
                        'name' => 'hook',
                        'options' => array(
                            'query' => $this->module->hooks,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'required' => true,
                        'form_group_class' => 'st-hook',
                        'hint' => $this->l('This field is required.'),
                        'desc' => $this->l('Select specific position where you want to display the product list.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Product Count'),
                        'name' => 'product_count',
                        'maxlength' => 2,
                        'class' => 'input fixed-width-xs',
                        'desc' => $this->l('Set the number of products displayed in this position. Default product count is 8.'),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enabled'),
                        'name' => 'active',
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'tab' => 'general'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use Carousel'),
                        'name' => 'is_carousel',
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'desc' => $this->l('Display products in crousel instead of list.'),
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Carousel width'),
                        'name' => 'width',
                        'maxlength' => 4,
                        'desc' => $this->l('Set crousel width in pixels eg: 500, or leave it empty to adapt the available width on the specified position in front office.'),
                        'class' => 'input fixed-width-xl',
                        'form_group_class' => 'st-crousel hide',
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use navigation'),
                        'name' => 'nav',
                        'form_group_class' => 'st-crousel hide',
                        'desc' => $this->l('Show Carousel navigation (Next/Prev) to slide the product in the carousel.'),
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use dots'),
                        'name' => 'pager',
                        'form_group_class' => 'st-crousel hide',
                        'desc' => $this->l('Show product pagination dots in the carousel.'),
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Navigation & dots color'),
                        'name' => 'color',
                        'form_group_class' => 'st-crousel hide',
                        'desc' => $this->l('Set the navigation (Next/Prev) and pager dots color in the carousel.'),
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto Slide'),
                        'name' => 'auto',
                        'form_group_class' => 'st-crousel hide',
                        'desc' => $this->l('Set to products slide automatically in the carousel.'),
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'tab' => 'carousel'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Hover Pause'),
                        'name' => 'pause',
                        'form_group_class' => 'st-crousel st-auto-crousel hide',
                        'desc' => $this->l('Set to products slide pause on mouse hover event in case of auto slide enabled.'),
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        ),
                        'tab' => 'carousel'
                    ),
                ),
                'buttons' => array(
                    'submit' => array(
                        'title' => $this->l('Save'),
                        'name' => 'btnSubmit',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                    ),
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'btnSaveAndStaySubmit',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                    )
                )
            );

        return parent::renderForm();
    }

    public function ajaxProcessSearchProduct()
    {
        if ($products = Product::searchByName(
            (int)$this->context->language->id,
            pSql(Tools::getValue('search_text'))
        )) {
            foreach ($products as &$product) {
                $image = Product::getCover($product['id_product']);
                $product['image'] = Context::getContext()->link->getImageLink(
                    $this->module->name,
                    $image['id_image'],
                    ImageType::getFormattedName('small')
                );
            }
            $data = array(
                'products' => $products,
                'found' => true
            );
        } else {
            $data = array(
                'found' => false
            );
        }
        die(Tools::jsonEncode($data)) ;
    }

    public function getFilterProducts($filter_products, $idLang)
    {
        $products = array();
        if ($filter_products) {
            foreach ($filter_products as $id_product) {
                $ObjProduct = new Product($id_product, false, $idLang);
                if ($ObjProduct) {
                    $image = Product::getCover($id_product);
                    $imageUrl = Context::getContext()->link->getImageLink(
                        $this->module->name,
                        $image['id_image'],
                        ImageType::getFormattedName('small')
                    );
                    $products[] = array(
                        'id_product' => $id_product,
                        'image' => $imageUrl,
                        'reference' => $ObjProduct->reference,
                        'name' => $ObjProduct->name
                    );
                }
            }
        }
        $this->context->smarty->assign('products', $products);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.
        '/views/templates/admin/filter_product.tpl');
    }

    public function setType($filter_type)
    {
        foreach ($this->module->types as $type) {
            if ($type['id'] == $filter_type) {
                return $type['name'];
            }
        }
        return $filter_type;
    }
    public function setHookValue($hook)
    {
        foreach ($this->module->hooks as $hooks) {
            if ($hooks['id'] == $hook) {
                return $hooks['name'];
            }
        }
        return $hook;
    }
    public function setName($name)
    {
        if (empty($name)) {
            return '---';
        }
        return $name;
    }
    public function setLayout($is_carousel)
    {
        if ($is_carousel) {
            return $this->l('Carousel');
        }
        return $this->l('List');
    }
}
