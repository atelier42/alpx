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

class AdminStickersBannersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'ProductLabel';
        $this->table = 'fmm_stickersbanners';
        $this->identifier = 'stickersbanners_id';
        $this->lang = true;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bootstrap = true;
        parent::__construct();
        $this->context = Context::getContext();

        $this->fields_list = array(
            'stickersbanners_id' => array(
                'title'     => '#',
                'width' => 25
            ),
            'title' => array(
                'title'     => $this->module->l('Title'),
                'width' => 'auto'
            ),
            'start_date' => array(
                'title'     => $this->module->l('Start'),
                'width' => 'auto',
                'type' => 'date'
            ),
            'expiry_date' => array(
                'title'     => $this->module->l('End'),
                'width' => 'auto',
                'type' => 'date'
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
                )
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
        $languages = Language::getLanguages();
        $module = new ProductLabelsandStickers;
        $current_object = $this->loadObject(true);
        $id = (int)Tools::getValue('stickersbanners_id');

        $back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex.'&token='.$this->token;
        }

        $shops = '';
        $selected_shops = '';
        if (Shop::isFeatureActive() && $id) {
            $shops = $this->renderShops();
            $assoc_shops = ProductLabel::getShopLabels($id);
            $selected_shops = ($current_object && $assoc_shops = ProductLabel::getShopLabels($id))? implode(',', $assoc_shops) : '';
        }
        $this->context->smarty->assign(array('shops' => $shops, 'selected_shops' => $selected_shops));
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
            'class' => 'button'
        );

        $this->context->smarty->assign('mode', $this->display);
        $this->context->smarty->assign('current_lang', $this->context->language->id);
        $this->context->smarty->assign('languages', $languages);
        $this->context->smarty->assign('module', $module);
        $this->context->smarty->assign('current_object', $current_object);
        $this->context->smarty->assign('stickersbanners_id', (int)$id);

        $color = '';
        $bg_color = '';
        $font = '';
        $font_size = 0;
        $border_color = '';
        $font_weight = '';
        $start_date = '';
        $expiry_date = '';
        if ($id != 0) {
            $this->context->smarty->assign('stickersbanners_id', $id);
            $colors = $current_object->getColors($id);
            $color = $colors['color'];
            $bg_color = $colors['bg_color'];
            $border_color = $colors['border_color'];
            $font = $current_object->font;
            $font_size = $current_object->font_size;
            $font_weight = $current_object->font_weight;
            $start_date = $current_object->start_date;
            $expiry_date = $current_object->expiry_date;
        }
        $informations = _PS_MODULE_DIR_.'productlabelsandstickers/views/templates/admin/stickers_banners/info.tpl';
        $this->context->smarty->assign(
            array(
                'show_toolbar' => true,
                'toolbar_btn' => $this->toolbar_btn,
                'toolbar_scroll' => $this->toolbar_scroll,
                'title' => array($this->l('Product labels and Stickers')),
                'currentToken' => $this->token,
                'currentIndex' => self::$currentIndex,
                'informations' => $informations,
                'currentTab' => $this,
                '_PS_MODULE_DIR_' => _PS_MODULE_DIR_,
                'base_uri' => __PS_BASE_URI__,
                'color' => $color,
                'bg_color' => $bg_color,
                'font' => $font,
                'font_size' => (int)$font_size,
                'border_color' => $border_color,
                'font_weight' => $font_weight,
                'start_date' => $start_date,
                'expiry_date' => $expiry_date,
            )
        );
        return parent::renderForm();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        Media::addJsDef(array('colorpicker_assets_path' => $this->module->getPathUri() . 'views/img/'));
        $this->addJs(array(
            __PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js',
            $this->module->getPathUri().'views/js/color-picker-assests.js',
        ));
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddfmm_stickersbanners')) {
            parent::postProcess();
            $current_object = $this->loadObject(true);
            $id = (int)Tools::getValue('stickersbanners_id');
            $id_lang = Context::getContext()->language->id;
            if (!$id_lang) {
                $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            }

            $sticker_text = Tools::getValue('sticker_text'.$id_lang);
            if (!empty($sticker_text)) {
                $languages = Language::getLanguages(true);
                $exists = (int)$current_object->getStickerIdStatic($id);
                if ($exists > 0) {
                    foreach ($languages as $language) {
                        $current_object->updateLabelText($id, (int)$language['id_lang'], Tools::getValue('sticker_text'.(int)$language['id_lang']));
                    }
                } else {
                    foreach ($languages as $language) {
                        $current_object->insertLabelText($id, (int)$language['id_lang'], Tools::getValue('sticker_text'.(int)$language['id_lang']));
                    }
                }
            }

            $shops = Tools::getValue('checkBoxShopAsso_'.$this->table);
            // adding shop data
            if (Shop::isFeatureActive() && $current_object->id) {
                ProductLabel::removeShopLabels($current_object->id);
                if (isset($shops) && $shops) {
                    foreach ($shops as $id_shop) {
                        ProductLabel::insertShopLabels($current_object->id, $id_shop);
                    }
                }
            } else {
                if ($current_object->id) {
                    ProductLabel::removeShopLabels($current_object->id);
                    ProductLabel::insertShopLabels($current_object->id, $this->context->shop->id);
                }
            }
        }
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['save']);
        unset($this->toolbar_btn['cancel']);
    }

    public function renderShops()
    {
        $this->fields_form = array(
            'form' => array(
                'id_form' => 'field_shops',
                'input' => array(
                    array(
                        'type' => 'shop',
                        'label' => $this->l('Shop association:'),
                        'name' => 'checkBoxShopAsso',
                    ),
                )
            )
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('sticker_id');
        $helper->identifier = $this->identifier;
        $helper->tpl_vars = array_merge(array(
                'languages' => $this->getLanguages(),
                'id_language' => $this->context->language->id
        ));
        return $helper->renderAssoShop();
    }

    public function getShopValues($object)
    {
        return array('shop' => $this->getFieldValue($object, 'shop'));
    }
}
