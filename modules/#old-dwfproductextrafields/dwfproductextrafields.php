<?php
/**
 *   Copyright since 2009 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright since 2009 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class DwfProductExtraFields extends Module
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->name = 'dwfproductextrafields';
        $this->tab = 'front_office_features';
        $this->version = '1.7.43';
        $this->author = 'ohmyweb';
        $this->displayName = $this->l('Product Extra Fields');
        $this->description = $this->l('Add extra fields to the product page');
        $this->module_key = '6f6189603f7433c3ccbd3e9eb19aa692';
        $this->author_address = '0x8F66ED89875e0CC587f27Fb5166B633388A602c7';

        parent::__construct();

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

        if (parent::install()
            && $this->installFiles()
            && DwfProductExtraFieldsClass::createTable()
            && $this->installDB()
            && $this->registerHook('actionDispatcherBefore')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionAdminProductsControllerSaveAfter')
            && $this->registerHook('actionAdminProductsControllerDuplicateAfter')
            && $this->registerHook('actionAdminProductsControllerDeleteAfter')
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('displayProductExtraFields')
            && $this->registerHook('displayProductExtraContent')
            && $this->registerHook('actionGetProductPropertiesAfter')
            && $this->registerHook('addWebserviceResources')
        ) {
            return true;
        }

        return false;
    }

    public function installDB()
    {
        $prefix = _DB_PREFIX_;
        $engine = _MYSQL_ENGINE_;

        $statements = array();

        $statements[] = "
            CREATE TABLE IF NOT EXISTS `${prefix}product_extra_field`(
                `id_product_extra_field` INT(10) NOT NULL AUTO_INCREMENT,
                `id_shop_default` INT(10) NOT NULL,
                `id_product` INT(10) NOT NULL,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_product_extra_field`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ";

        $statements[] = "
            CREATE TABLE IF NOT EXISTS `${prefix}product_extra_field_shop` (
                `id_product_extra_field` int(10) NOT NULL,
                `id_shop` int(10) NOT NULL,
                PRIMARY KEY (`id_product_extra_field`, `id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ";

        $statements[] = "
            CREATE TABLE IF NOT EXISTS `${prefix}product_extra_field_lang`(
                `id_product_extra_field` INT(10) NOT NULL,
                `id_shop` INT(11) NOT NULL,
                `id_lang` INT(10) unsigned NOT NULL,
                PRIMARY KEY (`id_product_extra_field`, `id_shop`, `id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ";

        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                $this->_errors[] = $this->l('Unable to execute the query : '.$statement);
                return false;
            }
        }

        return true;
    }

    public function installFiles()
    {
        $res = true;
        if (!file_exists(_PS_ROOT_DIR_._PS_JS_DIR_.'jquery/plugins/jquery.unserialize.js')) {
            copy(_PS_MODULE_DIR_ . $this->name.'/views/js/jquery.unserialize.js', _PS_ROOT_DIR_.'/js/jquery/plugins/jquery.unserialize.js');
        }
        return $res;
    }

    public function uninstall()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

        if (parent::uninstall()
            && DwfProductExtraFieldsClass::deleteDbTable()
            && $this->uninstallDB()
        ) {
            return true;
        }
        return false;
    }

    public function uninstallDB()
    {
        $prefix = _DB_PREFIX_;

        $statements = array();

        $statements[] = "DROP TABLE IF EXISTS `${prefix}product_extra_field`";
        $statements[] = "DROP TABLE IF EXISTS `${prefix}product_extra_field_shop`";
        $statements[] = "DROP TABLE IF EXISTS `${prefix}product_extra_field_lang`";

        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                return false;
            }
        }

        return true;
    }

    public function getContent()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

        if (Tools::getValue('ajax')) {
            $this->dispatchAjax();
        }

        $html_before = '';
        $html_after = '';
        $id_dwfproductextrafields = (int)Tools::getValue('id_dwfproductextrafields');

        if (Tools::isSubmit('savedwfproductextrafields')) {
            if ($id_dwfproductextrafields = Tools::getValue('id_dwfproductextrafields')) {
                $dwfproductextrafields = new DwfProductExtraFieldsClass((int)$id_dwfproductextrafields);
                $confMessageId = 4;
            } else {
                $dwfproductextrafields = new DwfProductExtraFieldsClass();
                $confMessageId = 3;
            }
            $dwfproductextrafields->copyFromPost();

            if ($dwfproductextrafields->validateFields(false, true) && $dwfproductextrafields->validateFieldsLang(false, true)) {
                if ($dwfproductextrafields->isValidFieldname()) {
                    $dwfproductextrafields->save();

                    Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf='.$confMessageId);
                } else {
                    $html_before .= $this->displayError($this->l('This fieldname already exists in database.'));
                }
            } else {
                $html_before .= $this->displayError($this->l('An error occurred while attempting to save.'));
            }
        }

        if (Tools::isSubmit('updatedwfproductextrafields') || Tools::isSubmit('adddwfproductextrafields')) {
            $this->context->controller->addJqueryUi('ui.sortable');
            $this->context->controller->addJqueryPlugin('unserialize');
            $this->context->controller->addJS($this->_path.'views/js/dwfproductextrafields.js?v='.$this->version);
            $this->context->controller->addCSS($this->_path.'views/css/dwfproductextrafields.css', 'all');

            $helper = $this->initForm();
            $this->context->smarty->assign(array(
                'languages'       => Language::getLanguages(false),
                'default_lang'    => (int)Configuration::get('PS_LANG_DEFAULT'),
            ));
            if ($id_dwfproductextrafields) {
                $dwfproductextrafields = new DwfProductExtraFieldsClass((int)$id_dwfproductextrafields);
                $helper->fields_value['fieldname'] = $dwfproductextrafields->fieldname;
                foreach (Language::getLanguages(false) as $lang) {
                    if (!empty($dwfproductextrafields->label[(int)$lang['id_lang']])) {
                        $helper->fields_value['label'][(int)$lang['id_lang']] = $dwfproductextrafields->label[(int)$lang['id_lang']];
                    } else {
                        $helper->fields_value['label'][(int)$lang['id_lang']] = '';
                    }
                    if (!empty($dwfproductextrafields->hint[(int)$lang['id_lang']])) {
                        $helper->fields_value['hint'][(int)$lang['id_lang']] = $dwfproductextrafields->hint[(int)$lang['id_lang']];
                    } else {
                        $helper->fields_value['hint'][(int)$lang['id_lang']] = '';
                    }
                }

                $helper->fields_value['type'] = $dwfproductextrafields->type;
                $helper->fields_value['location'] = $dwfproductextrafields->location;
                $helper->fields_value['active'] = (int)$dwfproductextrafields->active;

                $this->context->smarty->assign(array(
                    'field' => (array)$dwfproductextrafields,
                ));

                if ($dwfproductextrafields->location == 'vars') {
                    $html_after .= $this->display(__FILE__, 'views/templates/admin/info.tpl');
                }
            } else {
                $helper->fields_value['fieldname'] = Tools::getValue('fieldname');
                foreach (Language::getLanguages(false) as $lang) {
                    $helper->fields_value['label'][(int)$lang['id_lang']] = Tools::getValue('label_'.(int)$lang['id_lang'], '');
                    $helper->fields_value['hint'][(int)$lang['id_lang']] = Tools::getValue('hint_'.(int)$lang['id_lang'], '');
                }

                $helper->fields_value['type'] = Tools::getValue('type');
                $helper->fields_value['location'] = Tools::getValue('location');
                $helper->fields_value['active'] = (int)Tools::getValue('active');
            }

            $helper->fields_value['config'] = $this->display(__FILE__, 'views/templates/admin/config-empty.tpl');

            if ($id_dwfproductextrafields = Tools::getValue('id_dwfproductextrafields')) {
                $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_dwfproductextrafields');
                $helper->fields_value['id_dwfproductextrafields'] = (int)$id_dwfproductextrafields;
            }

            return $html_before.$helper->generateForm($this->fields_form).$html_after;
        } elseif (Tools::isSubmit('deletedwfproductextrafields')) {
            $dwfproductextrafields = new DwfProductExtraFieldsClass((int)$id_dwfproductextrafields);
            $dwfproductextrafields->delete();
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::getIsset('statusdwfproductextrafields')) {
            $dwfproductextrafields = new DwfProductExtraFieldsClass((int)$id_dwfproductextrafields);
            if ($dwfproductextrafields->active == 0) {
                $dwfproductextrafields->active = 1;
            } else {
                $dwfproductextrafields->active = 0;
            }
            $dwfproductextrafields->save();
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::getValue('action') == 'updatePositions') {
            $this->ajaxUpdatePositions();
        } else {
            if (Tools::getValue('id_dwfproductextrafields') && Tools::getIsset('position') && Tools::getIsset('way')) {
                $productextrafields = new DwfProductExtraFieldsClass((int)Tools::getValue('id_dwfproductextrafields'));
                $productextrafields->updatePosition(Tools::getValue('way'), Tools::getValue('position'));
            }

            $helper = $this->initList();

            Media::addJsDef(array(
                'module_name' => $this->name,
                'module_identifier' => 'id_' . $this->name,
                'currentIndex' => AdminController::$currentIndex.'&configure='.$this->name
            ));
            return $html_before.$helper->generateList($this->getListContent((int)Configuration::get('PS_LANG_DEFAULT')), $this->fields_list);
        }
    }

    public function ajaxProcessGetConfigByType()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

        $type = Tools::getValue('type');
        if ($id_field = (int)Tools::getValue('id_field')) {
            $field = new DwfProductExtraFieldsClass($id_field);
        }
        $return = array();
        $return['config'] = null;
        $return['html'] = null;
        $return['location'] = false;

        $this->context->smarty->assign(array(
            'languages'       => Language::getLanguages(false),
            'default_lang'    => (int)Configuration::get('PS_LANG_DEFAULT'),
        ));

        if (isset($field) && Validate::isLoadedObject($field)) {
            if ($type == $field->type) {
                $return['config'] = $field->config;
            }
        }
        $this->context->smarty->assign(array(
            'jsonConfig' => $return['config']
        ));
        switch ($type) {
            case 'selector':
                $return['html'] = $this->display(__FILE__, 'views/templates/admin/config-selector.tpl');
                break;
            case 'repeater':
                $return['html'] = $this->display(__FILE__, 'views/templates/admin/config-repeater.tpl');
                break;
            case 'textarea_mce':
            case 'textarea':
                $return['location'] = true;
                break;
        }

        die(json_encode($return));
    }

    public function dispatchAjax()
    {
        switch (Tools::getValue('action')) {
            case 'getMedias':
                $this->displayAjaxGetMedias();
                break;
            case 'callOrderImages':
                $this->displayAjaxCallOrderImages();
                break;
            case 'callAddImage':
                $this->displayAjaxCallAddImage();
                break;
            case 'callDeleteImage':
                $this->displayAjaxCallDeleteImage();
                break;
            case 'updatePositions':
                $this->ajaxUpdatePositions();
                break;
        }
        die;
    }

    public function displayAjaxCallAddImage()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';
        $product = new Product((int)Tools::getValue('id_product'));
        if (!Validate::isLoadedObject($product)) {
            $files = array();
            $files[0]['error'] = Tools::displayError('Cannot add image because product creation failed.');
        }

        $res = false;
        $field = Tools::getValue('field');
        $fieldname = Tools::getValue('fieldname');
        $fieldType = Tools::getValue('fieldType', 'image');
        $image_uploader = new HelperImageUploader($field);
        $image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'));
        $files = $image_uploader->process();

        foreach ($files as &$file) {
            if (isset($file['error']) && (!is_numeric($file['error']) || $file['error'] != 0)) {
                continue;
            }

            $new_path = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/';
            $new_file = uniqid(). '_' . $file['name'];

            $error = 0;
            if (!ImageManager::resize($file['save_path'], $new_path.$new_file, null, null, 'jpg', false, $error)) {
                switch ($error) {
                    case ImageManager::ERROR_FILE_NOT_EXIST:
                        $file['error'] = Tools::displayError('An error occurred while copying image, the file does not exist anymore.');
                        break;

                    case ImageManager::ERROR_FILE_WIDTH:
                        $file['error'] = Tools::displayError('An error occurred while copying image, the file width is 0px.');
                        break;

                    case ImageManager::ERROR_MEMORY_LIMIT:
                        $file['error'] = Tools::displayError('An error occurred while copying image, check your memory limit.');
                        break;

                    default:
                        $file['error'] = Tools::displayError('An error occurred while copying image.');
                        break;
                }

                continue;
            }

            unlink($file['save_path']);
            //Necesary to prevent hacking
            unset($file['save_path']);

            $file_infos = pathinfo($new_file);

            $file['basename'] = $file_infos['filename'];
            $file['status']   = 'ok';
            $file['file']     = $new_file;
            $file['path']     = $this->context->shop->physical_uri . $this->getExtraFieldImagePath($product->id).'/';

            $image = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$new_file;
            $image_thumbnail = ImageManager::thumbnail($image, 'extrafield_'.$field.'.jpg', 100, 'jpg', true, true);
            $image_size = file_exists($image) ? filesize($image) / 1000 : false;

            $file['thumbnail'] = $image_thumbnail ? str_replace('../img/tmp/', _PS_TMP_IMG_, $image_thumbnail) : false;
            $file['size_kb'] = $image_size . 'kb';

            @unlink(_PS_TMP_IMG_DIR_.$field.'_'.(int)$product->id.'.jpg');
//             @unlink(_PS_TMP_IMG_DIR_.$field.'_mini_'.(int)$product->id.'_'.$this->context->shop->id.'.jpg');

            $medias = array();
            $extrafields = ProductExtraField::getByIdProduct($product->id);
            if ($fieldType == 'gallery' && $extrafields->{$fieldname}) {
                $medias = explode(',', $extrafields->{$fieldname});
            }
            $medias[] = $new_file;
            $extrafields->{$fieldname} = implode(',', $medias);
            $res = $extrafields->save();
        }

        die(json_encode(array('status' => $res, $image_uploader->getName() => $files)));
    }

    public function displayAjaxCallDeleteImage()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

        $product = new Product((int)Tools::getValue('id_product'));
        $file = Tools::getValue('file');
        if (!Validate::isLoadedObject($product) || !$file) {
            $files = array();
            $files[0]['error'] = Tools::displayError('Cannot add image because product creation failed.');
        }

        if ($repeaterReference = Tools::getValue('reference')) {
            $field = Tools::getValue('field');
            $repeaterData = explode('|', $repeaterReference);
            $extrafields = ProductExtraField::getByIdProduct($product->id);
            if (!empty($repeaterData[0]) && $extrafields->{$repeaterData[0]}) {
                $languages = Language::getLanguages(false);
                foreach ($languages as $lang) {
                    if ($extrafields->{$repeaterData[0]}[$lang['id_lang']]) {
                        $repeater = json_decode($extrafields->{$repeaterData[0]}[$lang['id_lang']], true);
                        if ($repeater && count($repeater)) {
                            foreach ($repeater as $i => &$rep) {
                                if (!empty($repeaterData[2]) && $rep[$repeaterData[2]] && $rep[$repeaterData[2]] == $file) {
                                    unlink(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$file);
                                    $rep[$repeaterData[2]] = '';
                                }
                            }
                            $extrafields->{$repeaterData[0]}[$lang['id_lang']] = json_encode($repeater);
                            $extrafields->save();
                        }
                    }
                }
                $file_infos = pathinfo($file);
                die(json_encode(array($field => $files, 'basename' => $file_infos['filename'], 'deleted' => $file)));
            }
        } else {
            $field = Tools::getValue('field');
            $extrafields = ProductExtraField::getByIdProduct($product->id);
            if ($extrafields->{$field}) {
                $medias = explode(',', $extrafields->{$field});
                if ($file) {
                    unlink(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$file);

                    $newMedias = implode(',', array_diff($medias, array($file)));
                    $extrafields->{$field} = $newMedias;
                    $extrafields->save();

                    $files = array();
                    if ($newMedias) {
                        foreach ($newMedias as $newMedia) {
                            $files[] = array('name' => $newMedia);
                        }
                    }

                    $file_infos = pathinfo($file);

                    die(json_encode(array($field => $files, 'basename' => $file_infos['filename'], 'deleted' => $file)));
                }
            }
        }
    }

    public function getExtraFieldImagePath($id)
    {
        return 'upload';
    }

    protected function getListContent($id_lang)
    {
        return Db::getInstance()->ExecuteS('
            SELECT pef.`id_dwfproductextrafields`, pef.`fieldname`, pefl.`label`, pefl.`hint`, pef.`type`, pef.`config`, pef.`location`, pef.`position`, pef.active
            FROM `'._DB_PREFIX_.'dwfproductextrafields` pef, `'._DB_PREFIX_.'dwfproductextrafields_lang` pefl
            WHERE pef.id_dwfproductextrafields = pefl.id_dwfproductextrafields
            AND pefl.`id_lang` = '.(int)$id_lang.'
            ORDER BY pef.position ASC');
    }

    protected function initForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('New Product Extra Field.'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Fieldname'),
                    'name' => 'fieldname',
                    'size' => 33,
                    'required' => true,
                    'hint' => $this->l('Forbidden characters:').' !<>,;?=+()@#�{}-$%:'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Label'),
                    'lang' => true,
                    'name' => 'label',
                    'size' => 33,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Hint'),
                    'lang' => true,
                    'name' => 'hint',
                    'size' => 33,
                    'hint' => $this->l('This message will be displayed on the product admin page, above the field, to help you fill this')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Type'),
                    'name' => 'type',
                    'required' => false,
                    'default_value' => 'text',
                    'options' => array(
                        'query' => array(
                            array('key' => 'textarea_mce', 'name' => $this->l('Textarea with tinymce editor')),
                            array('key' => 'textarea', 'name' => $this->l('Textarea')),
                            array('key' => 'text', 'name' => $this->l('Text')),
                            array('key' => 'non-translatable_text', 'name' => $this->l('non-translatable text')),
                            array('key' => 'checkbox', 'name' => $this->l('Checkbox')),
                            array('key' => 'selector', 'name' => $this->l('Selector')),
                            array('key' => 'integer', 'name' => $this->l('Integer')),
                            array('key' => 'decimal', 'name' => $this->l('Decimal')),
                            array('key' => 'price', 'name' => $this->l('Price')),
                            array('key' => 'date', 'name' => $this->l('Date')),
                            array('key' => 'datetime', 'name' => $this->l('Datetime')),
                            array('key' => 'image', 'name' => $this->l('Image')),
                            array('key' => 'color', 'name' => $this->l('Color')),
                            array('key' => 'repeater', 'name' => $this->l('Repeater')),
                        ),
                        'name' => 'name',
                        'id' => 'key'
                    )
                ),
                array(
                    'type' => 'free',
                    'name' => 'config'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Location'),
                    'name' => 'location',
                    'required' => false,
                    'default_value' => 'tab',
                    'options' => array(
                        'query' => array(
                            array('key' => 'tab', 'name' => $this->l('Product tab')),
                            array('key' => 'vars', 'name' => $this->l('Product var')),
                        ),
                        'name' => 'name',
                        'id' => 'key'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => ((isset($this->bootstrap) && $this->bootstrap == true)?'btn btn-default pull-right':'button')
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'dwfproductextrafields';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'savedwfproductextrafields';
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' =>
                array(
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                    'desc' => $this->l('Back to list')
                )
        );
        return $helper;
    }

    protected function initList()
    {
        $this->fields_list = array(
            'id_dwfproductextrafields' => array(
                'title' => $this->l('Id'),
                'width' => 50,
                'type' => 'text',
            ),
            'label' => array(
                'title' => $this->l('Label'),
                'width' => 140,
                'type' => 'text',
                'filter_key' => 'pefl!label'
            ),
            'type' => array(
                'title' => $this->l('Type'),
                'width' => 70,
                'type' => 'text',
                'filter_key' => 'pef!type'
            ),
            'location' => array(
                'title' => $this->l('Location'),
                'width' => 70,
                'type' => 'text',
                'filter_key' => 'pef!location'
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => 70,
                'filter_key' => 'pef!position',
                'align' => 'center',
                'position' => 'position'
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 25,
                'filter_key' => 'pef!active',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false
            ),
        );

        if (Shop::isFeatureActive()) {
            $this->fields_list['id_shop'] = array('title' => $this->l('ID Shop'), 'align' => 'center', 'width' => 25, 'type' => 'int');
        }

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_dwfproductextrafields';
        $helper->position_identifier = 'id_dwfproductextrafields';
        $helper->orderBy = 'position';
        $helper->orderWay = 'ASC';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->listTotal = count($this->getListContent((int)Configuration::get('PS_LANG_DEFAULT')));
        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper;
    }

    /* Modify attribute position */
    public function ajaxUpdatePositions()
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

        $way = (int)Tools::getValue('way');
        $id_dwfproductextrafields = (int)Tools::getValue('id');
        $positions = Tools::getValue('dwfproductextrafields');

        if (is_array($positions)) {
            foreach ($positions as $position => $value) {
                $pos = explode('_', $value);
                if (isset($pos[2]) && (int)$pos[2] === $id_dwfproductextrafields) {
                    if ($productextrafields = new DwfProductExtraFieldsClass((int)$pos[2])) {
                        if (isset($position) && $productextrafields->updatePosition($way, $position)) {
                            echo 'ok position '.(int)$position.' for extrafield '.(int)$pos[2]."\r\n";
                        } else {
                            echo '{"hasError" : true, "errors" : "Can not update extrafield '.(int)$id_dwfproductextrafields.' to position '.(int)$position.' "}';
                        }
                    } else {
                        echo '{"hasError" : true, "errors" : "This extrafield ('.(int)$id_dwfproductextrafields.') can t be loaded"}';
                    }

                    break;
                }
            }
        }
    }

    public function hookActionDispatcherBefore()
    {
        require_once _PS_MODULE_DIR_ . 'dwfproductextrafields/models/ProductExtraField.php';

        $this->context->smarty->registerPlugin('modifier', 'productExtraFieldValue', array('ProductExtraField', 'getProductExtraFieldValue'));
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        if ($this->context->controller->controller_name == 'AdminProducts') {
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            if (count($active_fields)) {
                foreach ($active_fields as $field) {
                    if ($field->type == 'color') {
                        $this->context->controller->addJqueryPlugin('colorpicker');
                        return;
                    }
                }
            }
        }
    }

    public function hookActionGetProductPropertiesAfter($params)
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

        $active_fields = DwfProductExtraFieldsClass::getActiveFields();
        if (count($active_fields)) {
            $extra_field = ProductExtraField::getByIdProduct($params['product']['id_product']);

            foreach ($active_fields as $field) {
                if ($field->location == 'vars' && $extra_field->id) {
                    if ($field->isMultilangField()) {
                        if ($field->type == 'repeater') {
                            $repeater = json_decode($extra_field->{$field->fieldname}[$params['id_lang']], true);
                            if ($repeater && count($repeater)) {
                                $config = json_decode($field->config, true);
                                $keys = array_keys(array_column($config['elements'], 'type'), 'image');
                                if (count($keys)) {
                                    foreach ($repeater as &$el) {
                                        foreach ($keys as $key) {
                                            if (isset($el[$config['elements'][$key]['key']]) && $el[$config['elements'][$key]['key']] && file_exists(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($params['product']['id_product']).'/'.$el[$config['elements'][$key]['key']])) {
                                                $el[$config['elements'][$key]['key']] = $this->context->link->getMediaLink($this->context->shop->physical_uri . $this->getExtraFieldImagePath($params['product']['id_product']).'/'.$el[$config['elements'][$key]['key']]);
                                            } else {
                                                $el[$config['elements'][$key]['key']] = null;
                                            }
                                        }
                                    }
                                }
                            }
                            $params['product']['dwf_'.$field->fieldname] = $repeater;
                        } else {
                            $params['product']['dwf_'.$field->fieldname] = $extra_field->{$field->fieldname}[$params['id_lang']];
                        }
                    } elseif ($field->type == 'image') {
                        if ($extra_field->{$field->fieldname} && file_exists(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($params['product']['id_product']).'/'.$extra_field->{$field->fieldname})) {
                            $params['product']['dwf_' . $field->fieldname] = $this->context->link->getMediaLink($this->context->shop->physical_uri . $this->getExtraFieldImagePath($params['product']['id_product']) . '/' . $extra_field->{$field->fieldname});
                        } else {
                            $params['product'][ 'dwf_' . $field->fieldname ] = null;
                        }
                    } elseif ($field->type == 'selector') {
                        $params['product']['dwf_'.$field->fieldname] = $extra_field->{$field->fieldname};

                        // Retrieve label for selector field type
                        $label = '';
                        $config = json_decode($field->config, true);
                        $key = array_search($extra_field->{$field->fieldname}, array_column($config['values'], 'value'));
                        if (false !== $key) {
                            $currentValue = $config['values'][$key];
                            $keyLang = array_search($this->context->language->id, array_column($currentValue['label'], 'id_lang'));
                            if (false !== $keyLang) {
                                // label for current language
                                $label = $currentValue['label'][$keyLang]['value'];
                            }

                            if (!$label) {
                                $default_language = (int)Configuration::get('PS_LANG_DEFAULT');
                                $keyLang = array_search($default_language, array_column($currentValue['label'], 'id_lang'));
                                if (false !== $keyLang) {
                                    // if label for current language, getting the label for default
                                    $label = $currentValue['label'][$keyLang]['value'];
                                }
                            }
                        }

                        $params['product']['dwf_'.$field->fieldname.'_label'] = $label;
                    } else {
                        $params['product']['dwf_'.$field->fieldname] = $extra_field->{$field->fieldname};
                    }
                } else {
                    $params['product']['dwf_'.$field->fieldname] = null;
                }
            }
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)$params['id_product']))) {
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

            $iso_tiny_mce = $this->context->language->iso_code;
            $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            $iso_tiny_mce = (file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$iso_tiny_mce.'.js') ? $iso_tiny_mce : 'en');
            $languages = Language::getLanguages(false);
            $this->context->smarty->assign(array(
                'languages' => $this->context->controller->_languages,
                'defaultFormLanguage' => (int)Configuration::get('PS_LANG_DEFAULT'),
                'ad' => dirname($_SERVER['PHP_SELF']),
                'iso_tiny_mce' => $iso_tiny_mce,
                'currentIndex_modules' => 'index.php?controller=AdminModules&configure='.$this->name,
                'token_modules' => Tools::getAdminTokenLite('AdminModules')
            ));

            if (Tools::getIsset('deleteImageExtraField')) {
                $field_name = Tools::getValue('deleteImageExtraField');
                $extra_field = ProductExtraField::getByIdProduct($product->id);
                unlink(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$extra_field->{$field_name});
                $extra_field->{$field_name} = '';
                $extra_field->save();

                Tools::redirectAdmin(AdminController::$currentIndex.'&id_product='.$product->id.'&updateproduct&key_tab=ModuleDwfproductextrafields&token='.Tools::getAdminTokenLite('AdminProducts'));
            }

            $fields = array();
            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            foreach ($active_fields as $field) {
                $extra_field = ProductExtraField::getByIdProduct($product->id);
                $field_el = array(
                    'id'        => $field->fieldname.'_'.$field->id,
                    'label'     => $field->label,
                    'hint'      => $field->hint,
                    'name'      => $field->fieldname,
                    'value'     => $extra_field->{$field->fieldname},
                    'type'      => $field->type,
                    'is_multi_lang' => $field->isMultilangField(),
                    'location'  => $field->location
                );

                if ($field_el['type'] == 'selector') {
                    $config = json_decode($field->config, true);
                    $choices = array();
                    if ($config['values']) {
                        foreach ($config['values'] as $val) {
                            $key = array_search($default_lang, array_column($val['label'], 'id_lang'));
                            $choices[] = array('key' => $val['value'], 'name' => $val['label'][$key]['value']);
                        }
                        $field_el['choices'] = $choices;
                        $field_el['multiple'] = (bool)$config['multiple'];
                    }
                    $field_el['value'] = explode(',', $extra_field->{$field->fieldname});
                } elseif ($field_el['type'] == 'repeater') {
                    $config = json_decode($field->config, true);
                    $elements = array();
                    if ($config['elements']) {
                        $values = array();
                        foreach ($languages as $lang) {
                            $jsonValues = $extra_field->{$field->fieldname}[$lang['id_lang']];
                            if ($jsonValues) {
                                $arrayValues = json_decode($jsonValues, true);
                                for ($i=0; $i<count($arrayValues); $i++) {
                                    foreach ($config['elements'] as $k => $el) {
                                        $key = array_search($default_lang, array_column($el['name'], 'id_lang'));
                                        $values[$i][$k]['type'] = $el['type'];
                                        $values[$i][$k]['id'] = $el['key'];
                                        $values[$i][$k]['name'] = $el['name'][$key]['value'];
                                        if (in_array($el['type'], array('text', 'textarea', 'textarea_mce'))) {
                                            $values[$i][$k]['value'][$lang['id_lang']] = (isset($arrayValues[$i][$el['key']]) ? $arrayValues[$i][$el['key']] : '');
                                        } else {
                                            $values[$i][$k]['value'] = (isset($arrayValues[$i][$el['key']]) ? $arrayValues[$i][$el['key']] : '');
                                        }

                                        if ($el['type'] == 'image' && $values[$i][$k]['value']) {
                                            $image = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$values[$i][$k]['value'];
                                            if (file_exists($image)) {
                                                $image_url = ImageManager::thumbnail($image, 'extrafield_' . $field->fieldname . '-' . $el['key'] . $i . '.jpg', 100, 'jpg', true, true);
                                                $image_size = file_exists($image) ? filesize($image) / 1000 : false;

                                                $file_infos = pathinfo($values[$i][$k]['value']);

                                                $values[$i][$k]['basename'] = $file_infos['filename'];
                                                $values[$i][$k]['image_zoom'] = $image_url ? $this->context->shop->physical_uri . $this->getExtraFieldImagePath($product->id) . '/' . $values[$i][$k]['value'] : false;
                                                $values[$i][$k]['image'] = $image_url ? str_replace('../img/tmp/', _PS_TMP_IMG_, $image_url) : false;
                                                $values[$i][$k]['size'] = $image_size;
                                                $values[$i][$k]['delete_url'] = AdminController::$currentIndex . '&id_product=' . $product->id . '&updateproduct&key_tab=ModuleDwfproductextrafields&token=' . Tools::getAdminTokenLite('AdminProducts') . '&deleteImageExtraField=' . $field_el['name'];
                                            } else {
                                                $values[$i][$k]['value'] = '';
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($config['elements'] as $el) {
                            $key = array_search($default_lang, array_column($el['name'], 'id_lang'));
                            $elements[] = array(
                                'type'  => $el['type'],
                                'id'    => $el['key'],
                                'name'  => $el['name'][$key]['value'],
                            );
                        }
                        $field_el['collapsed_default'] = (bool)$config['collapsed_default'];
                        $field_el['elements'] = $elements;
                        $field_el['values'] = $values;
                    }
                } elseif ($field_el['type'] == 'image') {
                    if ($field_el['value']) {
                        $image = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($product->id).'/'.$field_el['value'];
                        if (file_exists($image)) {
                            $image_url = ImageManager::thumbnail($image, 'extrafield_' . $field->fieldname . '.jpg', 100, 'jpg', true, true);
                            $image_size = file_exists($image) ? filesize($image) / 1000 : false;

                            $file_infos = pathinfo($field_el['value']);

                            $field_el['basename'] = $file_infos['filename'];
                            $field_el['image_zoom'] = $image_url ? $this->context->shop->physical_uri . $this->getExtraFieldImagePath($product->id) . '/' . $field_el['value'] : false;
                            $field_el['image'] = $image_url ? str_replace('../img/tmp/', _PS_TMP_IMG_, $image_url) : false;
                            $field_el['size'] = $image_size;
                            $field_el['delete_url'] = AdminController::$currentIndex . '&id_product=' . $product->id . '&updateproduct&key_tab=ModuleDwfproductextrafields&token=' . Tools::getAdminTokenLite('AdminProducts') . '&deleteImageExtraField=' . $field_el['name'];
                        } else {
                            $field_el['value'] = '';
                        }
                    }
                    $field_el['upload_url'] = AdminController::$currentIndex.'&id_product='.$product->id.'&updateproduct&key_tab=ModuleDwfproductextrafields&token='.Tools::getAdminTokenLite('AdminProducts').'&fieldType=image&uploadImageExtraField='.$field_el['name'];
                }

                $fields[] = $field_el;
            }

            $this->context->smarty->assign(array(
                'product_id' => $product->id,
                'warnings' => array(),
                'fields' => $fields,
                'iso_tiny_mce' => $iso_tiny_mce,
            ));

            return $this->display(__FILE__, 'views/templates/admin/adminProduct-extraFields.tpl');
        }
    }

    public function hookActionAdminProductsControllerSaveAfter($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

            $extraFieldsValues = Tools::getValue('dwfproductextrafields');

            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            if (count($active_fields)) {
                $languages = Language::getLanguages(false);
                $extra_field = ProductExtraField::getByIdProduct($product->id);
                foreach ($active_fields as $field) {
                    if ($field->type == 'image') {
                        if (!empty($extraFieldsValues[$field->fieldname.'_files'])) {
                            $extra_field->{$field->fieldname} = $extraFieldsValues[$field->fieldname.'_files'];
                        }
                    } else {
                        if ($field->type == 'repeater') {
                            $repeater = $extraFieldsValues[$field->fieldname];
                            if (is_array($repeater)) {
                                if (isset($repeater['LINEID'])) {
                                    unset($repeater['LINEID']);
                                }

                                if (count($repeater)) {
                                    $config = json_decode($field->config, true);
                                    if ($config['elements']) {
                                        $i = 0;
                                        $value = array();
                                        foreach ($repeater as $rep) {
                                            foreach ($config['elements'] as $element) {
                                                if (in_array($element['type'], array('text', 'textarea', 'textarea_mce'))) {
                                                    foreach ($languages as $lang) {
                                                        if (isset($rep[$element['key'] . '_' . $lang['id_lang']])) {
                                                            $value[$lang['id_lang']][$i][$element['key']] = $rep[$element['key'] . '_' . $lang['id_lang']];
                                                        } else {
                                                            $value[$lang['id_lang']][$i][$element['key']] = '';
                                                        }
                                                    }
                                                } elseif ($element['type'] == 'image') {
                                                    foreach ($languages as $lang) {
                                                        if (isset($rep[$element['key'] . '_files'])) {
                                                            $value[$lang['id_lang']][$i][$element['key']] = $rep[$element['key'] . '_files'];
                                                        } else {
                                                            $value[$lang['id_lang']][$i][$element['key']] = '';
                                                        }
                                                    }
                                                } else {
                                                    foreach ($languages as $lang) {
                                                        if ($element['type'] == 'checkbox') {
                                                            $value[$lang['id_lang']][$i][$element['key']] = 0;
                                                        }

                                                        if (isset($rep[$element['key']])) {
                                                            $value[$lang['id_lang']][$i][$element['key']] = $rep[$element['key']];
                                                        } else {
                                                            $value[$lang['id_lang']][$i][$element['key']] = '';
                                                        }
                                                    }
                                                }
                                            }
                                            $i++;
                                        }
                                    }

                                    foreach ($languages as $lang) {
                                        $extra_field->{$field->fieldname}[$lang['id_lang']] = json_encode($value[$lang['id_lang']]);
                                    }
                                } else {
                                    foreach ($languages as $lang) {
                                        $extra_field->{$field->fieldname}[$lang['id_lang']] = '';
                                    }
                                }
                            }
                        } elseif ($field->type != 'selector' && $field->isMultilangField()) {
                            foreach ($languages as $lang) {
                                if (isset($extraFieldsValues[$field->fieldname.'_'.$lang['id_lang']])) {
                                    $extra_field->{$field->fieldname}[$lang['id_lang']] = $extraFieldsValues[$field->fieldname.'_'.$lang['id_lang']];
                                }
                            }
                        } else {
                            if ($field->type == 'checkbox') {
                                $extra_field->{$field->fieldname} = 0;
                            }

                            if (isset($extraFieldsValues[$field->fieldname])) {
                                if (is_array($extraFieldsValues[$field->fieldname])) {
                                    $extra_field->{$field->fieldname} = implode(',', $extraFieldsValues[$field->fieldname]);
                                } else {
                                    if (in_array($field->type, array('price', 'decimal'))) {
                                        $extra_field->{$field->fieldname} = (float)$extraFieldsValues[$field->fieldname];
                                    } elseif ($field->type == 'integer') {
                                        $extra_field->{$field->fieldname} = (int)$extraFieldsValues[$field->fieldname];
                                    } elseif ($field->type == 'datetime') {
                                        if ($extraFieldsValues[$field->fieldname]) {
                                            $extra_field->{$field->fieldname} = date('Y-m-d H:i:s', strtotime($extraFieldsValues[$field->fieldname]));
                                        } else {
                                            $extra_field->{$field->fieldname} = null;
                                        }
                                    } elseif ($field->type == 'date') {
                                        if ($extraFieldsValues[$field->fieldname]) {
                                            $extra_field->{$field->fieldname} = date('Y-m-d', strtotime($extraFieldsValues[$field->fieldname]));
                                        } else {
                                            $extra_field->{$field->fieldname} = null;
                                        }
                                    } else {
                                        $extra_field->{$field->fieldname} = $extraFieldsValues[$field->fieldname];
                                    }
                                }
                            }
                        }
                    }
                }
                $extra_field->save();
            }
        }
    }

    /**
     * @param $params
     */
    public function hookActionAdminProductsControllerDuplicateAfter($params)
    {
        if (!empty($params['duplicate_product_id'])) {
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            if (count($active_fields)) {
                $extra_field = ProductExtraField::getByIdProduct($params['product_id']);
                $duplicatedField = new ProductExtraField();
                $duplicatedField->id_product = $params['duplicate_product_id'];
                foreach ($active_fields as $field) {
                    if ($field->type == 'image') {
                        $duplicatedField->{$field->fieldname} = '';
                        if (file_exists(_PS_ROOT_DIR_.$this->getExtraFieldImagePath($extra_field->id_product).'/'.$extra_field->{$field->fieldname})) {
                            $new_path = _PS_ROOT_DIR_.$this->getExtraFieldImagePath($duplicatedField->id_product).'/';
                            $new_file = uniqid().Tools::strtolower(Tools::substr($extra_field->{$field->fieldname}, -5));

                            if (ImageManager::resize(_PS_ROOT_DIR_.$this->getExtraFieldImagePath($extra_field->id_product).'/'.$extra_field->{$field->fieldname}, $new_path.$new_file, null, null, 'jpg', false)) {
                                $duplicatedField->{$field->fieldname} = $new_file;
                            }
                        }
                    } else {
                        $duplicatedField->{$field->fieldname} = $extra_field->{$field->fieldname};
                        if ($field->type == 'repeater') {
                            $config = json_decode($field->config, true);
                            $keys = array_keys(array_column($config['elements'], 'type'), 'image');
                            if (count($keys)) {
                                $languages = Language::getLanguages(false);
                                $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
                                $repeaterDefault = json_decode($duplicatedField->{$field->fieldname}[$default_lang], true);
                                foreach ($repeaterDefault as &$el) {
                                    foreach ($keys as $key) {
                                        $originalImage = $el[$config['elements'][$key]['key']];
                                        $el[$config['elements'][$key]['key']] = null;
                                        if (isset($originalImage) && $originalImage && file_exists(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($extra_field->id_product).'/'.$originalImage)) {
                                            $new_path = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($duplicatedField->id_product).'/';
                                            $new_file = uniqid().Tools::strtolower(Tools::substr($originalImage, -5));
                                            if (ImageManager::resize(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$this->getExtraFieldImagePath($extra_field->id_product).'/'.$originalImage, $new_path.$new_file, null, null, 'jpg', false)) {
                                                $el[$config['elements'][$key]['key']] = $new_file;
                                            }
                                        }
                                    }
                                }
                                $duplicatedField->{$field->fieldname}[$default_lang] = json_encode($repeaterDefault);

                                if (count($languages) > 1) {
                                    foreach ($languages as $lang) {
                                        if ($lang['id_lang'] != $default_lang) {
                                            foreach ($repeaterDefault as $i => $el) {
                                                foreach ($keys as $key) {
                                                    $repeater = json_decode($duplicatedField->{$field->fieldname}[$lang['id_lang']], true);
                                                    $repeater[$i][$config['elements'][$key]['key']] = $el[$config['elements'][$key]['key']];
                                                }
                                            }
                                            $duplicatedField->{$field->fieldname}[$lang['id_lang']] = json_encode($repeater);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $duplicatedField->save();
            }
        }
    }

    public function hookActionAdminProductsControllerDeleteAfter($params)
    {
        $id_product = false;
        if (!empty($params['product_id'])) {
            $id_product = (int)$params['product_id'];
        } elseif (!empty($params['product_list_id'])) {
            $id_product = (int)$params['product_list_id'];
        }
        if ($id_product) {
            require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

            $extra_field = ProductExtraField::getByIdProduct($id_product);
            $extra_field->delete();
        }
    }

    public function hookdisplayProductExtraFields($params)
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

        if (!empty($params['id_product'])) {
            $id_product = (int)$params['id_product'];
        } elseif (!empty(Tools::getValue('id_product'))) {
            $id_product = (int)Tools::getValue('id_product');
        } else {
            return;
        }

        $fields = array();
        $extra_field = ProductExtraField::getByIdProduct($id_product);
        if ($extra_field->id) {
            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            foreach ($active_fields as $field) {
                if ($field->location == 'tab' && $extra_field->{$field->fieldname}[Context::getContext()->language->id]) {
                    $fields[] = array(
                        'id' => $field->id,
                        'label' => $field->label[Context::getContext()->language->id],
                        'content' => $extra_field->{$field->fieldname}[Context::getContext()->language->id],
                    );
                }
            }
        }
        $this->smarty->assign('fields_name', $fields);

        return $this->display(__FILE__, 'display-extrafields.tpl');
    }

    public function hookDisplayProductExtraContent($params)
    {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';

        $fields = array();
        $extra_field = ProductExtraField::getByIdProduct($params['product']->id);
        if ($extra_field->id) {
            $active_fields = DwfProductExtraFieldsClass::getActiveFields();
            foreach ($active_fields as $field) {
                if ($field->location == 'tab' && $extra_field->{$field->fieldname}[Context::getContext()->language->id]) {
                    $fields[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                        ->setTitle($field->label[Context::getContext()->language->id])
                        ->setContent($extra_field->{$field->fieldname}[Context::getContext()->language->id])
                        ->setAttr(array(
                            'id' => $field->id,
                            'class' => 'extra_'.$field->id
                        ));
                }
            }
        }
        return $fields;
    }

    public function hookAddWebserviceResources($params) {
        require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/ProductExtraField.php';
        return [
            'product_extra_fields' => [
                'description' => 'The product extra fields',
                'class' => 'ProductExtraField'
            ],
        ];
    }
}
