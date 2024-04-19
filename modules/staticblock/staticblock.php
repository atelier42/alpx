<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    FME Modules
 *  @copyright © 2020 FME Modules
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once dirname(__FILE__) . '/models/StaticblockModel.php';
include_once dirname(__FILE__) . '/models/StaticReassuranceModel.php';
include_once dirname(__FILE__) . '/models/StaticblockTemplates.php';
include_once(_PS_ROOT_DIR_ . '/modules/staticblock/models/Widget.php');

class StaticBlock extends Widget
{
    public $labelTranslations = array();

    protected static $module_hooks;

    protected static $currentTab;

    protected $editor_themes;
    public function __construct()
    {
        $this->name = 'staticblock';
        $this->tab = 'front_office_features';
        $this->version = '2.2.0';
        $this->author = 'FMM Modules';
        $this->need_instance = 1;
        $this->module_key = 'bcb5d28dee8655a123d642e94b0c6cb7';
        $this->author_address = '0xcC5e76A6182fa47eD831E43d80Cd0985a14BB095';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Static Blocks');
        $this->description = $this->l('Create any content you can imagine and add them to any place on the page.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        self::$module_hooks = array(
            'top',
            'home',
            'header',
            'footer',
            'payment',
            'leftColumn',
            'rightColumn',
            'displayStaticBlock',
            'displayNav1', //17
            'displayNav2', //17
            'displaySearch', //17
            'displayNotFound', //17
            'displayFooterAfter', //17
            'displayReassurance', //17
            'displayShoppingCart',
            'displayMaintenance', //17
            'displayFooterBefore', //17
            'displayFooterProduct',
            'displayNavFullWidth', //17
            'displayPaymentReturn',
            'displayCustomerAccount',
            'displayNotificationInfo', //17
            'displayLeftColumnProduct',
            'displayRightColumnProduct',
            'displayOrderConfirmation',
            'displayPaymentByBinaries', //17
            'displayNotificationError', //17
            'displayOrderConfirmation1', //17
            'displayOrderConfirmation2',
            'displayShoppingCartFooter',
            'displayCheckoutSummaryTop', //17
            'displayProductExtraContent', //17
            'displayNotificationSuccess', //17
            'displayNotificationWarning', //17
            'displayCMSDisputeInformation', //17
            'displayProductAdditionalInfo', //17
            'displayCustomerLoginFormAfter', //17
            'displayCrossSellingShoppingCart', //17
        );

        $this->editor_themes = array(
            'default',
            '3024-day',
            '3024-night',
            'abcdef',
            'ambiance',
            'base16-dark',
            'base16-light',
            'bespin',
            'blackboard',
            'cobalt',
            'colorforth',
            'darcula',
            'dracula',
            'duotone-dark',
            'duotone-light',
            'eclipse',
            'elegant',
            'erlang-dark',
            'gruvbox-dark',
            'hopscotch',
            'icecoder',
            'idea',
            'isotope',
            'lesser-dark',
            'liquibyte',
            'lucario',
            'material',
            'mbo',
            'mdn-like',
            'midnight',
            'monokai',
            'neat',
            'neo',
            'night',
            'oceanic-next',
            'panda-syntax',
            'paraiso-dark',
            'paraiso-light',
            'pastel-on-dark',
            'railscasts',
            'rubyblue',
            'seti',
            'shadowfox',
            'solarizeddark',
            'solarizedlight',
            'the-matrix',
            'tomorrow-night-bright',
            'tomorrow-night-eighties',
            'ttcn',
            'twilight',
            'vibrant-ink',
            'xq-dark',
            'xq-light',
            'yeti',
            'zenburn',
        );
        $this->labelTranslations = array(
            'REASSURANCE_COLLAPSE_TITLE_COLOR' => $this->l('*Enter valid collapse animation title color'),
            'REASSURANCE_COLLAPSE_SUB_TITLE_COLOR' => $this->l('*Enter valid collapse animation subtitle color'),
            'REASSURANCE_COLLAPSE_DESCRIPTION_COLOR' => $this->l('*Enter valid collapse animation description color'),
            'REASSURANCE_COLLAPSE_HOVER_COLOR' => $this->l('*Enter valid collapse animation hover color'),
            'REASSURANCE_COLLAPSE_BACKGROUND_COLOR' => $this->l('*Enter valid collapse animation background color'),
            'REASSURANCE_COLLAPSE_LINK_COLOR' => $this->l('*Enter valid collapse animation link color'),
            'REASSURANCE_COLLAPSE_ARROW_COLOR' => $this->l('*Enter valid collapse animation arrow color'),
            'REASSURANCE_TOOLTIP_TITLE_COLOR' => $this->l('*Enter valid tooltip animation title color'),
            'REASSURANCE_TOOLTIP_SUB_TITLE_COLOR' => $this->l('*Enter valid tooltip animation subtitle color'),
            'REASSURANCE_TOOLTIP_DESCRIPTION_COLOR' => $this->l('*Enter valid tooltip animation description color'),
            'REASSURANCE_TOOLTIP_BACKGROUND_COLOR' => $this->l('*Enter valid tooltip animation background color'),
            'REASSURANCE_TOOLTIP_BACK_HOVER_COLOR' => $this->l('*Enter valid tooltip animation hover backgound color'),
            'REASSURANCE_HOVER_COLOR' => $this->l('*Enter valid hover animation color'),
            'REASSURANCE_HOVER_TITLE_COLOR' => $this->l('*Enter valid hover title color'),
            'REASSURANCE_HOVER_SUB_TITLE_COLOR' => $this->l('*Enter valid hover sub title color'),
            'REASSURANCE_HOVER_BACKGROUND_COLOR' => $this->l('*Enter valid hover backgound color'),
            'REASSURANCE_HOVER2_COLOR' => $this->l('*Enter valid hover2 animation color'),
            'REASSURANCE_HOVER2_TITLE_COLOR' => $this->l('*Enter valid hover2 title color'),
            'REASSURANCE_HOVER2_SUB_TITLE_COLOR' => $this->l('*Enter valid hover2 sub title color'),
            'REASSURANCE_HOVER2_BACKGROUND_COLOR' => $this->l('*Enter valid hover2 backgound color'),
        );
    }

    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';
        mkdir(_PS_IMG_DIR_ . 'sbr', 0777, true);
        StaticReassuranceModel::addDefaultValues();
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        if (parent::install()) {
            foreach (self::$module_hooks as $hook) {
                if (!$this->registerHook($hook)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';
        rename(_PS_IMG_DIR_ . 'sbr', _PS_IMG_DIR_ . 'sbr' . rand(pow(10, 3 - 1), pow(10, 3) - 1));
        StaticReassuranceModel::deleteDefaultValues();
        if (parent::uninstall()) {
            foreach (self::$module_hooks as $hook) {
                if (!$this->unregisterHook($hook)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function getContent()
    {
        $output = '';
        $custom_hook = $this->display(__FILE__, 'views/templates/hook/hook_info.tpl');
        self::$currentTab = 'blocks';
        $this->postProcess();
        if (Tools::isSubmit('add' . $this->name) ||
            Tools::isSubmit('update' . $this->name)) {
            return $this->renderBlockForm();
        } elseif (Tools::isSubmit('add' . $this->name . '_reassuranceblock') ||
            Tools::isSubmit('update' . $this->name . '_reassuranceblock')) {
            return $this->renderReassuranceForm();
        } elseif (Tools::isSubmit('add' . $this->name . '_template') ||
            Tools::isSubmit('update' . $this->name . '_templates')) {
            return $this->renderTemplateForm();
        } elseif (Tools::isSubmit('add' . $this->name . '_customhook')) {
            $admin_url = AdminController::$currentIndex . '&configure=' . $this->name.'&save' . $this->name . '_customhook&token='.Tools::getAdminTokenLite('AdminModules');
            $this->context->smarty->assign(array(
                'admin_url' => $admin_url,
            ));
            $this->context->smarty->assign('version', _PS_VERSION_);
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/add_customhook.tpl'
            );
        }
        $this->context->smarty->assign('currentTab', self::$currentTab);
        $this->context->smarty->assign('renderSettingsForm', $this->renderSettingsForm());
        $this->context->smarty->assign('renderBlockList', $this->renderBlockList());
        $this->context->smarty->assign('version', _PS_VERSION_);
        $this->context->smarty->assign('renderTemplateList', $this->renderTemplateList());
        $this->context->smarty->assign('renderReassuranceList', $this->renderReassuranceList());
        $this->context->smarty->assign('renderReassuranceSetting', $this->renderReassuranceSetting());
        $this->context->smarty->assign('renderCustomhook', $this->renderCustomhook());
        return $custom_hook. $output . $this->display($this->_path, 'views/templates/admin/config.tpl');
    }

    private function postProcess()
    {
        if (Tools::getValue('updatePositions')) {
            $way = (int) Tools::getValue('way');
            $positions = Tools::getValue('module-staticblock');
            if (isset($positions) && $positions) {
                foreach ($positions as $position => $value) {
                    $pos = explode('_', $value);
                    if (isset($pos[2]) && $pos[2]) {
                        if (isset($position) && StaticblockModel::updatePosition($pos[2], $position, $way)) {
                            echo 'ok position ' . (int) $position .
                            ' for id ' . (int) $pos[1] . '\r\n';
                        } else {
                            echo '{"hasError" : true, "errors" : "Can not update id ' .
                            (int) $pos[2] . ' to position ' . (int) $position . ' "}';
                        }
                    }
                }
            }
        } elseif (Tools::isSubmit('savestaticblock_customhook')) {
            $title_custom_hook = Tools::getValue('title_custom_hook');
            $name_custom_hook = Tools::getValue('name_custom_hook');
            $name_custom_hook = preg_replace("/[^a-zA-Z]+/", "", $name_custom_hook);
            
            StaticblockModel::addHooks($name_custom_hook, $title_custom_hook);
            $this->registerHook($name_custom_hook);
            return $this->context->controller->confirmations[] = $this->l('Updated Successfully');
        } elseif (Tools::isSubmit('deletestaticblock_customhook')) {
            $id = (int) Tools::getValue('id');
            $del_hook = StaticblockModel::deleteHooks($id);
            return $this->context->controller->confirmations[] = $this->l('Delete Successfully');
        } elseif (Tools::isSubmit('saveSettings')) {
            self::$currentTab = 'settings';
            Configuration::updateValue(
                'STATIC_BLOCK_EDITOR_THEME',
                Tools::getValue('STATIC_BLOCK_EDITOR_THEME')
            );
            return $this->context->controller->confirmations[] = $this->l('Settings updated successfully.');
        } elseif (Tools::isSubmit('save' . $this->name)) {
            self::$currentTab = 'blocks';
            $function = 'add';
            $id_static_block = (int) Tools::getValue('id_static_block');
            $position = (int) Tools::getValue('position');
            if ($id_static_block) {
                $staticBlock = new StaticblockModel($id_static_block);
                $function = 'update';
            } else {
                $staticBlock = new StaticblockModel();
                $staticBlock->position = $this->beforeAdd($position);
            }

            $staticBlock->hook = Tools::getValue('hook');
            $staticBlock->editor = Tools::getValue('editor');
            $staticBlock->id_static_block_template = (int) Tools::getValue('id_static_block_template');
            $staticBlock->status = (int) Tools::getValue('status');
            $staticBlock->custom_css = Tools::getValue('custom_css');
            $staticBlock->title_active = (int) Tools::getValue('title_active');
            $staticBlock->css = Tools::getValue('css');
            $staticBlock->date_from = Tools::getValue('date_from');
            $staticBlock->date_to = Tools::getValue('date_to');
            $staticBlock->groupBox = Tools::getValue('groupBox');
            if (Shop::isFeatureActive()) {
                StaticblockModel::delShopBlock($id_static_block);
                $shops = Tools::getValue('checkBoxShopAsso_static_block');
                if (!empty($shops)) {
                    foreach ($shops as $id_shop) {
                        if ($id_static_block == '0') {
                            $id_static_block = $staticBlock->getLastId();
                        }
                        StaticblockModel::insertBlockShop($id_static_block, $id_shop);
                    }
                }
            }
            $languages = Language::getLanguages(true);
            foreach ($languages as $language) {
                if (Tools::getValue('editor') == 2) {
                    $staticBlock->content[$language['id_lang']] = Tools::getValue('content1_' . $language['id_lang']);
                } elseif (Tools::getValue('editor') == 1) {
                    $staticBlock->content[$language['id_lang']] = Tools::getValue('content2_' . $language['id_lang']);
                } elseif (Tools::getValue('editor') == 3) {
                    $staticBlock->content[$language['id_lang']] = Tools::getValue('content3_' . $language['id_lang']);
                } else {
                    $staticBlock->content[$language['id_lang']] = Tools::getValue('content4_' . $language['id_lang']);
                }
                if (Tools::getValue('block_title_' . $language['id_lang']) &&
                    !Validate::isGenericName(Tools::getValue('block_title_' . $language['id_lang']))) {
                    $this->context->controller->errors[] = $this->l('Invalid date format for date_from.');
                } else {
                    $staticBlock->block_title[$language['id_lang']] = Tools::getValue(
                        'block_title_' . $language['id_lang']
                    );
                }
            }

            if (empty($staticBlock->hook) || !$staticBlock->hook) {
                $this->context->controller->errors[] = $this->l('Please select a hook.');
            }
            if ($staticBlock->date_from && !Validate::isDateFormat($staticBlock->date_from)) {
                $this->context->controller->errors[] = $this->l('Invalid date format for date_from.');
            }
            if ($staticBlock->date_to && !Validate::isDateFormat($staticBlock->date_to)) {
                $this->context->controller->errors[] = $this->l('Invalid date format for date_to.');
            }
            if ($staticBlock->date_from != '0000-00-00 00:00:00' &&
                $staticBlock->date_to != '0000-00-00 00:00:00' &&
                $staticBlock->date_from && $staticBlock->date_to &&
                strtotime($staticBlock->date_from) >= strtotime($staticBlock->date_to)) {
                $this->context->controller->errors[] = $this->l('Starting date should be smaller than ending date.');
            }

            if (!count($this->context->controller->errors)) {
                if (!call_user_func(array($staticBlock, $function))) {
                    return $this->context->controller->errors[] = sprintf(
                        $this->l('Something went wrong, trying to %s a static block.'),
                        $function
                    );
                } else {
                    $this->addConditionRule($staticBlock);
                    return $this->context->controller->confirmations[] = sprintf(
                        $this->l('Block %sed successfully.'),
                        $function
                    );
                }
            } else {
                return $this->context->controller->errors;
            }
        } elseif (Tools::isSubmit('save' . $this->name . '_reassuranceblock')) {
            self::$currentTab = 'reassurance';
            $function = 'add';
            $id_fmm_reassurance = (int) Tools::getValue('id_fmm_reassurance');
            if ($id_fmm_reassurance) {
                $reassuranceBlock = new StaticReassuranceModel($id_fmm_reassurance);
                $function = 'update';
            } else {
                $reassuranceBlock = new StaticReassuranceModel();
            }
            if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) &&
                !empty($_FILES['image']['tmp_name'])) {
                $timestamp = time();
                $file_name = $_FILES['image']['name'];
                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_name = pathinfo($file_name, PATHINFO_FILENAME);
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (!is_dir(_PS_IMG_DIR_ . 'sbr')) {
                    @mkdir(_PS_IMG_DIR_ . 'sbr', 0777, true);
                }
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    _PS_IMG_DIR_ . 'sbr/' . $file_name . '_' . $timestamp . '.' . $extension
                );
                $reassuranceBlock->image = $file_name . '_' . $timestamp . '.' . $extension;
            }
            $reassuranceBlock->status = (bool) Tools::getValue('status');
            $reassuranceBlock->link = pSQL(Tools::getValue('link'));
            $reassuranceBlock->apperance = pSQL(Tools::getValue('apperance'));
            $default_lang_id = $this->context->language->id;
            $languages = Language::getLanguages(true);
            if (empty(Tools::getValue('title_' . $default_lang_id))) {
                $this->context->controller->errors[] = $this->l('*Enter title for reassurance block.');
            }if (empty(Tools::getValue('sub_title_' . $default_lang_id))) {
                $this->context->controller->errors[] = $this->l('*Enter sub title for reassurance block.');
            }
            if (empty($reassuranceBlock->link) || !$reassuranceBlock->link) {
                $this->context->controller->errors[] = $this->l('*Please enter a link.');
            }
            foreach ($languages as $language) {
                if (Tools::getValue('title_' . $language['id_lang']) &&
                    !Validate::isGenericName(Tools::getValue('title_' . $language['id_lang']))) {
                    $this->context->controller->errors[] = $this->l(
                        'Enter valid title for reassurance block.'
                    );
                } else {
                    $reassuranceBlock->title[$language['id_lang']] = Tools::getValue('title_' . $language['id_lang']);
                }
                if (Tools::getValue('sub_title_' . $language['id_lang']) &&
                    !Validate::isGenericName(Tools::getValue('sub_title_' . $language['id_lang']))) {
                    $this->context->controller->errors[] = $this->l(
                        'Enter valid sub title for reassurance block.'
                    );
                } else {
                    $reassuranceBlock->sub_title[$language['id_lang']] = Tools::getValue(
                        'sub_title_' . $language['id_lang']
                    );
                }
                $reassuranceBlock->description[$language['id_lang']] = Tools::getValue(
                    'description_' . $language['id_lang']
                );
            }
            if (!count($this->context->controller->errors)) {
                if (!call_user_func(array($reassuranceBlock, $function))) {
                    return $this->context->controller->errors[] = sprintf(
                        $this->l('Something went wrong, trying to %s a template.'),
                        $function
                    );
                } else {
                    return $this->context->controller->confirmations[] = sprintf(
                        $this->l('Reassurance block %sed successfully.'),
                        $function
                    );
                }
            } else {
                $this->context->controller->errors;
            }
        } elseif (Tools::isSubmit('submit' . $this->name . '_template')) {
            self::$currentTab = 'templates';
            $function = 'add';
            $id_static_block_template = (int) Tools::getValue('id_static_block_template');
            if ($id_static_block_template) {
                $template = new StaticblockTemplates($id_static_block_template);
                $function = 'update';
            } else {
                $template = new StaticblockTemplates();
            }
            $template->template_name = Tools::getValue('template_name');
            $template->status = Tools::getValue('status');
            $template->code = Tools::getValue('code');
            if (empty($template->template_name) ||
                !Validate::isGenericName($template->template_name)) {
                $this->context->controller->errors[] = $this->l('Please enter a valid title for template.');
            }

            if (!count($this->context->controller->errors)) {
                if (!call_user_func(array($template, $function))) {
                    return $this->context->controller->errors[] = sprintf(
                        $this->l(
                            'Something went wrong, trying to %s a template.'
                        ),
                        $function
                    );
                } else {
                    return $this->context->controller->confirmations[] = sprintf(
                        $this->l(
                            'Template %sed successfully.'
                        ),
                        $function
                    );
                }
            } else {
                return $this->context->controller->errors;
            }
        } elseif (Tools::isSubmit('submit' . $this->name . '_rsetting')) {
            self::$currentTab = 'rsetting';
            $check = 1;
            $home = (int) Tools::getValue('REASSURANCE_HOME_ANIMATION');
            $footer = (int) Tools::getValue('REASSURANCE_FOOTER_ANIMATION');
            $product = (int) Tools::getValue('REASSURANCE_PRODUCT_ANIMATION');
            $colorThemes = Tools::getValue('color_theme');
            foreach ($colorThemes as $key => $color) {
                if (isset($color) && !Validate::isColor($color)) {
                    $this->context->controller->errors[] = sprintf(
                        $this->l('%s'),
                        $this->labelTranslations[$key]
                    );
                    $check = 0;
                } else {
                    Configuration::updateValue(
                        $key,
                        $color,
                        false,
                        $this->context->shop->id_shop_group,
                        $this->context->shop->id
                    );
                    Configuration::updateValue(
                        'REASSURANCE_HOME_ANIMATION',
                        $home,
                        false,
                        $this->context->shop->id_shop_group,
                        $this->context->shop->id
                    );
                    Configuration::updateValue(
                        'REASSURANCE_FOOTER_ANIMATION',
                        $footer,
                        false,
                        $this->context->shop->id_shop_group,
                        $this->context->shop->id
                    );
                    Configuration::updateValue(
                        'REASSURANCE_PRODUCT_ANIMATION',
                        $product,
                        false,
                        $this->context->shop->id_shop_group,
                        $this->context->shop->id
                    );
                }
            }
            if ($check == 1) {
                return $this->context->controller->confirmations[] = $this->l('Settings updated successfully.');
            }
        } elseif (Tools::isSubmit('delete' . $this->name)) {
            self::$currentTab = 'blocks';
            $id_static_block = Tools::getValue('id_static_block');
            $static_block = new StaticblockModel($id_static_block);
            if (!Validate::isLoadedObject($static_block)) {
                return $this->context->controller->errors[] = $this->l('Static block not found.');
            } else {
                if (!$static_block->delete()) {
                    return $this->context->controller->errors[] = $this->l('unsuccessful deletion.');
                } else {
                    return $this->context->controller->confirmations[] = $this->l('static block successfully deleted');
                }
            }
        } elseif (Tools::isSubmit('delete' . $this->name . '_templates')) {
            self::$currentTab = 'templates';
            $id_static_block_template = Tools::getValue('id_static_block_template');
            $static_block_template = new StaticblockTemplates($id_static_block_template);
            if (!Validate::isLoadedObject($static_block_template)) {
                return $this->context->controller->errors[] = $this->l('Template not found.');
            } else {
                if (!$static_block_template->delete()) {
                    return $this->context->controller->errors[] = $this->l('unsuccessful deletion.');
                } else {
                    return $this->context->controller->confirmations[] = $this->l('Template successfully deleted');
                }
            }
        } elseif (Tools::isSubmit('submitBulkdelete' . $this->name)) {
            self::$currentTab = 'blocks';
            $staticblockBox = Tools::getValue('staticblockBox');
            if (isset($staticblockBox) && $staticblockBox) {
                foreach ($staticblockBox as $id_static_block) {
                    if (Validate::isLoadedObject($static_block = new StaticblockModel($id_static_block))) {
                        $static_block->delete();
                    }
                }
                return $this->context->controller->confirmations[] = $this->l('selected blocks successfully deleted');
            }
        } elseif (Tools::isSubmit('delete' . $this->name . '_reassuranceblock')) {
            self::$currentTab = 'reassurance';
            $id_fmm_reassurance = Tools::getValue('id_fmm_reassurance');
            if (!Validate::isLoadedObject($fmm_reassurance = new StaticReassuranceModel($id_fmm_reassurance))) {
                return $this->context->controller->errors[] = $this->l('Reassurance Block not found.');
            } else {
                if (!$fmm_reassurance->delete()) {
                    return $this->context->controller->errors[] = $this->l('unsuccessful deletion.');
                } else {
                    return $this->context->controller->confirmations[] = $this->l(
                        'Reassurance Block successfully deleted'
                    );
                }
            }
        } elseif (Tools::isSubmit('submitBulkdelete' . $this->name . '_reassuranceblock')) {
            self::$currentTab = 'reassurance';
            $reassuranceBox = Tools::getValue('staticblock_reassuranceblockBox');
            $fmm_reassurance = new StaticReassuranceModel($id_fmm_reassurance);
            if (isset($reassuranceBox) && $reassuranceBox) {
                foreach ($reassuranceBox as $id_fmm_reassurance) {
                    if (Validate::isLoadedObject($fmm_reassurance)) {
                        $fmm_reassurance->delete();
                    }
                }
                return $this->context->controller->confirmations[] = $this->l('selected blocks successfully deleted');
            }
        } elseif (Tools::isSubmit('submitBulkdelete' . $this->name . '_templates')) {
            self::$currentTab = 'templates';
            $staticblock_templatesBox = Tools::getValue('staticblock_templatesBox');
            $static_block_template = new StaticblockTemplates($id_static_block_template);
            if (isset($staticblock_templatesBox) && $staticblock_templatesBox) {
                foreach ($staticblock_templatesBox as $id_static_block_template) {
                    if (Validate::isLoadedObject($static_block_template)) {
                        $static_block_template->delete();
                    }
                }
                return $this->context->controller->confirmations[] = $this->l(
                    'selected templates successfully deleted'
                );
            }
        } elseif (Tools::isSubmit('status' . $this->name) && Tools::getValue('id_static_block')) {
            self::$currentTab = 'blocks';
            $id_static_block = (int) Tools::getValue('id_static_block');
            if (!StaticblockModel::updateStatus('status', $id_static_block)) {
                return $this->context->controller->errors[] = $this->l('status update unsuccessful.');
            } else {
                return $this->context->controller->confirmations[] = $this->l('status successfully updated');
            }
        } elseif (Tools::isSubmit('status' . $this->name . '_templates') &&
            Tools::getValue('id_static_block_template')) {
            self::$currentTab = 'templates';
            $id_static_block_template = (int) Tools::getValue('id_static_block_template');
            if (!StaticblockTemplates::updateStatus('status', $id_static_block_template)) {
                return $this->context->controller->errors[] = $this->l('status update unsuccessful.');
            } else {
                return $this->context->controller->confirmations[] = $this->l('status successfully updated');
            }
        } elseif (Tools::isSubmit('status' . $this->name . '_reassuranceblock') &&
            Tools::getValue('id_fmm_reassurance')) {
            self::$currentTab = 'reassurance';
            $id_fmm_reassurance = (int) Tools::getValue('id_fmm_reassurance');
            if (!StaticReassuranceModel::updateStatus('status', $id_fmm_reassurance)) {
                return $this->context->controller->errors[] = $this->l('status update unsuccessful.');
            } else {
                return $this->context->controller->confirmations[] = $this->l('status successfully updated');
            }
        } elseif (Tools::isSubmit('title_active' . $this->name) && Tools::getValue('id_static_block')) {
            self::$currentTab = 'blocks';
            $id_static_block = (int) Tools::getValue('id_static_block');
            if (!StaticblockModel::updateStatus('title_active', $id_static_block)) {
                return $this->context->controller->errors[] = $this->l('status update unsuccessful.');
            } else {
                return $this->context->controller->confirmations[] = $this->l('status successfully updated');
            }
        }
    }

    private function addConditionRule($object)
    {
        if ($object instanceof StaticblockModel) {
            $object->deleteConditions();
            foreach ($_POST as $key => $values) {
                if (preg_match('/^condition_group_([0-9]+)$/Ui', $key, $condition_group)) {
                    $conditions = array();
                    foreach ($values as $value) {
                        $condition = explode('_', $value);
                        $conditions[] = array(
                            'type' => $condition[0],
                            'operator' => $condition[1],
                            'value' => $condition[2],
                        );
                    }
                    $object->addConditions($conditions);
                }
            }
        }
    }

    protected function beforeAdd($position)
    {
        if (empty($position) || !StaticblockModel::positionOccupied($position)) {
            $position = StaticblockModel::getHigherPosition() + 1;
        }
        return $position;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/staticblock.css', 'all');
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '<') == true) {
            $this->context->controller->addCSS($this->_path . 'views/css/static-modal.css', 'all');
        }
        $this->context->controller->addJs($this->_path . 'views/js/staticblock.js', 'all');
        // embedded code
        $id_group = 0;
        if ($this->context->customer->id) {
            $id_group = Customer::getDefaultGroupId((int) $this->context->customer->id);
        }
        if (!$id_group) {
            $id_group = (int) Group::getCurrent()->id;
        }

        $static_blocks = StaticblockModel::getAllBlocks(
            (int) $this->context->language->id,
            $this->context->shop->id,
            $id_group
        );
        $final_blocks = $this->applyTemplate($static_blocks);
        Media::addJsDef(array('static_blocks' => $final_blocks));
        $ps_17 = (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) ? 1 : 0;
        $this->context->smarty->assign('ps_17', (int) $ps_17);
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=') == true) {
            $jQuery_path = Media::getJqueryPath(_PS_JQUERY_VERSION_);
            if (is_array($jQuery_path) && isset($jQuery_path[0])) {
                $jQuery_path = $jQuery_path[0];
            }
            $this->context->smarty->assign(array('jQuery_path' => $jQuery_path));
        }
        return $this->display(__FILE__, 'views/templates/hook/embedded.tpl');
    }

    public function getHookBlocks($hook)
    {
        $blocks = array();
        $all_data = StaticblockModel::getAllHooks();
        $allow = 0;
        foreach ($all_data as $key => $value) {
            if ($value['hook_name'] == $hook) {
                $allow = 1;
            }
        }
        if ($hook && !empty($hook) && Validate::isString($hook) &&
            (in_array($hook, self::$module_hooks) || $allow == 1)) {
            $id_lang = $this->context->language->id;
            $id_shop = $this->context->shop->id;
            $id_group = null;
            $id_customer = (int) $this->context->customer->id;
            if ($id_customer) {
                $id_group = Customer::getDefaultGroupId((int) $id_customer);
            }
            if (!$id_group) {
                $id_group = (int) Group::getCurrent()->id;
            }
            $blocks = StaticblockModel::getBlockByHook(
                $hook,
                $id_shop,
                $id_lang,
                $id_group
            );
        }
        return $blocks;
    }

    public function getBlock($static_blocks = array(), $params = array())
    {
        if (isset($static_blocks) && $static_blocks) {
            $controller = Dispatcher::getInstance()->getController();
            $filtered_block = $this->applyCondition($static_blocks, $controller);
            $final_blocks = $this->applyTemplate($filtered_block);
            if (isset($final_blocks) && $final_blocks) {
                $this->context->smarty->assign('static_block', $final_blocks);
                return $this->display(__FILE__, 'views/templates/hook/staticblock.tpl');
            }
        }
    }

    public function getListHook($name, $row)
    {
        $hook = $this->getHookByName($name);
        if (empty($hook)) {
            $all_data = StaticblockModel::getAllHooks();
            foreach ($all_data as $key => $value) {
                if ($value['hook_name'] == $name) {
                    return $value['hook_name'];
                }
            }
        }
        return $hook['name'];
    }

    protected function getHooks()
    {
        $all_data = StaticblockModel::getAllHooks();
        $custom_hooks = array();
        foreach ($all_data as $key => $value) {
            $custom_hooks[] = array(
                        'id_option' => $value['hook_name'],
                        'name' => $value['hook_name'],
                    );
        }
        
        return array(
            'page' => array(
                'label' => $this->l('Page Hooks'),
                'value' => 'page',
                'available_hooks' => array(
                    'header' => $this->getHookByName('header'),
                    'top' => $this->getHookByName('top'),
                    'displaySearch' => $this->getHookByName('displaySearch'),
                    'footer' => $this->getHookByName('footer'),
                    'leftColumn' => $this->getHookByName('leftColumn'),
                    'rightColumn' => $this->getHookByName('rightColumn'),
                    'displayFooterBefore' => $this->getHookByName('displayFooterBefore'),
                    'displayFooterAfter' => $this->getHookByName('displayFooterAfter'),
                ),
            ),
            'landingpage' => array(
                'label' => $this->l('Landing Page'),
                'value' => 'home',
                'available_hooks' => array(
                    'home' => $this->getHookByName('home'),
                ),
            ),
            'product' => array(
                'label' => $this->l('Product Related Hooks'),
                'value' => 'product',
                'available_hooks' => array(
                    'extraLeft' => $this->getHookByName('extraLeft'),
                    'extraRight' => $this->getHookByName('extraRight'),
                    'displayFooterProduct' => $this->getHookByName('displayFooterProduct'),
                    'displayProductAdditionalInfo' => $this->getHookByName('displayProductAdditionalInfo'),
                    'displayProductExtraContent' => $this->getHookByName('displayProductExtraContent'),
                ),
            ),
            'customer' => array(
                'label' => $this->l('Customer Related Hooks'),
                'value' => 'customer',
                'available_hooks' => array(
                    'displayCustomerLoginFormAfter' => $this->getHookByName('displayCustomerLoginFormAfter'),
                    'displayCustomerAccount' => $this->getHookByName('displayCustomerAccount'),
                    'displayMyAccountBlockfooter' => $this->getHookByName('displayMyAccountBlockfooter'),
                ),
            ),
            'cart' => array(
                'label' => $this->l('Cart Related Hooks'),
                'value' => 'cart',
                'available_hooks' => array(
                    'displayShoppingCart' => $this->getHookByName('displayShoppingCart'),
                    'displayShoppingCartFooter' => $this->getHookByName('displayShoppingCartFooter'),
                    'displayCrossSellingShoppingCart' => $this->getHookByName('displayCrossSellingShoppingCart'),
                ),
            ),
            'order_checkout' => array(
                'label' => $this->l('Order and Checkout Related Hooks'),
                'value' => 'order_checkout',
                'available_hooks' => array(
                    'displayCheckoutSummaryTop' => $this->getHookByName('displayCheckoutSummaryTop'),
                    'displayOrderConfirmation' => $this->getHookByName('displayOrderConfirmation'),
                    'displayOrderConfirmation1' => $this->getHookByName('displayOrderConfirmation1'),
                    'displayOrderConfirmation2' => $this->getHookByName('displayOrderConfirmation2'),
                ),
            ),
            'payment' => array(
                'label' => $this->l('Payment Related Hooks'),
                'value' => 'payment',
                'available_hooks' => array(
                    'displayPaymentByBinaries' => $this->getHookByName('displayPaymentByBinaries'),
                    'displayPaymentReturn' => $this->getHookByName('displayPaymentReturn'),
                    'payment' => $this->getHookByName('payment'),
                ),
            ),
            'notification' => array(
                'label' => $this->l('Notification Related Hooks'),
                'value' => 'notification',
                'available_hooks' => array(
                    'displayNotificationError' => $this->getHookByName('displayNotificationError'),
                    'displayNotificationInfo' => $this->getHookByName('displayNotificationInfo'),
                    'displayNotificationSuccess' => $this->getHookByName('displayNotificationSuccess'),
                    'displayNotificationWarning' => $this->getHookByName('displayNotificationWarning'),
                ),
            ),
            'others' => array(
                'label' => $this->l('Others'),
                'value' => 'page',
                'available_hooks' => array(
                    'displayNav1' => $this->getHookByName('displayNav1'),
                    'displayNav2' => $this->getHookByName('displayNav2'),
                    'displayNavFullWidth' => $this->getHookByName('displayNavFullWidth'),
                    'displayNotFound' => $this->getHookByName('displayNotFound'),
                    'displayReassurance' => $this->getHookByName('displayReassurance'),
                    'displayMaintenance' => $this->getHookByName('displayMaintenance'),
                    'displayCMSDisputeInformation' => $this->getHookByName('displayCMSDisputeInformation'),
                ),
            ),

            'custom' => array(
                'label' => $this->l('Custom Hook'),
                'value' => 'page',
                'available_hooks' => array(
                    'displayNav1' => $this->getHookByName('displayStaticBlock'),
                ),
            ),
            'custom' => array(
                'label' => $this->l('Dynamic Hooks'),
                'value' => 'page',
                'available_hooks' => $custom_hooks
            ),
        );
    }

    protected function getHookByName($name)
    {
        if (!$name || !Validate::isHookName($name)) {
            return false;
        } else {
            switch ($name) {
                case 'header':
                    return array(
                        'id_option' => 'header',
                        'name' => $this->l('Page Header (displayHeader)'),
                    );
                case 'top':
                    return array(
                        'id_option' => 'top',
                        'name' => $this->l('Page Top (displayTop)'),
                    );
                case 'displaySearch':
                    return array(
                        'id_option' => 'displaySearch',
                        'name' => $this->l('Page Searchbar (displaySearch) - (for PS 1.7)'),
                    );
                case 'footer':
                    return array(
                        'id_option' => 'footer',
                        'name' => $this->l('Page Footer (displayFooter)'),
                    );
                case 'leftColumn':
                    return array(
                        'id_option' => 'leftColumn',
                        'name' => $this->l('Page Left Column (displayLeftColumn)'),
                    );
                case 'rightColumn':
                    return array(
                        'id_option' => 'rightColumn',
                        'name' => $this->l('Page Right Column (displayRightColumn)'),
                    );
                case 'displayFooterBefore':
                    return array(
                        'id_option' => 'displayFooterBefore',
                        'name' => $this->l('Page Footer Before (displayFooterBefore) - (for PS 1.7)'),
                    );
                case 'displayFooterAfter':
                    return array(
                        'id_option' => 'displayFooterAfter',
                        'name' => $this->l('Page Footer After (displayFooterAfter) - (for PS 1.7)'),
                    );
                case 'home':
                    return array(
                        'id_option' => 'home',
                        'name' => $this->l('Home Page (displayHome)'),
                    );
                case 'extraLeft':
                    return array(
                        'id_option' => 'extraLeft',
                        'name' => $this->l('Product Left Column (displayLeftColumnProduct)'),
                    );
                case 'extraRight':
                    return array(
                        'id_option' => 'extraRight',
                        'name' => $this->l('Product Right Column (displayRightColumnProduct)'),
                    );
                case 'displayFooterProduct':
                    return array(
                        'id_option' => 'displayFooterProduct',
                        'name' => $this->l('Product Footer (displayFooterProduct)'),
                    );
                case 'displayProductAdditionalInfo':
                    return array(
                        'id_option' => 'displayProductAdditionalInfo',
                        'name' => $this->l('Product Additional Info (displayProductAdditionalInfo) - (for PS 1.7)'),
                    );
                case 'displayProductExtraContent':
                    return array(
                        'id_option' => 'displayProductExtraContent',
                        'name' => $this->l('Product Extra Content (displayRightColumn) - (for PS 1.7)'),
                    );
                case 'displayCustomerLoginFormAfter':
                    return array(
                        'id_option' => 'displayCustomerLoginFormAfter',
                        'name' => $this->l('After Customer Login Form (displayCustomerLoginFormAfter) - (for PS 1.7)'),
                    );
                case 'displayCustomerAccount':
                    return array(
                        'id_option' => 'displayCustomerAccount',
                        'name' => $this->l('Customer Account Page (displayCustomerAccount)'),
                    );
                case 'displayMyAccountBlockfooter':
                    return array(
                        'id_option' => 'displayMyAccountBlockfooter',
                        'name' => $this->l('My Account Block Footer (displayMyAccountBlockfooter)'),
                    );
                case 'displayShoppingCart':
                    return array(
                        'id_option' => 'displayShoppingCart',
                        'name' => $this->l('Shopping Cart (displayShoppingCart)'),
                    );
                case 'displayShoppingCartFooter':
                    return array(
                        'id_option' => 'displayShoppingCartFooter',
                        'name' => $this->l('Shopping Cart Footer (displayShoppingCartFooter)'),
                    );
                case 'displayCrossSellingShoppingCart':
                    return array(
                        'id_option' => 'displayCrossSellingShoppingCart',
                        'name' => $this->l(
                            'Cross Selling on Shoping Cart(displayCrossSellingShoppingCart) - (for PS 1.7)'
                        ),
                    );
                case 'displayCheckoutSummaryTop':
                    return array(
                        'id_option' => 'displayCheckoutSummaryTop',
                        'name' => $this->l('Checkout Summary Top (displayCheckoutSummaryTop) - (for PS 1.7)'),
                    );
                case 'displayOrderConfirmation':
                    return array(
                        'id_option' => 'displayOrderConfirmation',
                        'name' => $this->l('Order Confirmation Page (displayOrderConfirmation)'),
                    );
                case 'displayOrderConfirmation1':
                    return array(
                        'id_option' => 'displayOrderConfirmation1',
                        'name' => $this->l('Order Confirmation 1 (displayOrderConfirmation1) - (for PS 1.7)'),
                    );
                case 'displayOrderConfirmation2':
                    return array(
                        'id_option' => 'displayOrderConfirmation2',
                        'name' => $this->l('Order Confirmation 2 (displayOrderConfirmation2)'),
                    );
                case 'displayPaymentByBinaries':
                    return array(
                        'id_option' => 'displayPaymentByBinaries',
                        'name' => $this->l('Payment Binaries Form (displayPaymentByBinaries) - (for PS 1.7)'),
                    );
                case 'displayPaymentReturn':
                    return array(
                        'id_option' => 'displayPaymentReturn',
                        'name' => $this->l('Payment Return (displayPaymentReturn)'),
                    );
                case 'payment':
                    return array(
                        'id_option' => 'payment',
                        'name' => $this->l('Payment Methods block'),
                    );
                case 'displayNotificationError':
                    return array(
                        'id_option' => 'displayNotificationError',
                        'name' => $this->l('Error Notification (displayNotificationError) - (for PS 1.7)'),
                    );
                case 'displayNotificationInfo':
                    return array(
                        'id_option' => 'displayNotificationInfo',
                        'name' => $this->l('Info Notification (displayNotificationInfo) - (for PS 1.7)'),
                    );
                case 'displayNotificationSuccess':
                    return array(
                        'id_option' => 'displayNotificationSuccess',
                        'name' => $this->l('Success Notification (displayNotificationSuccess) - (for PS 1.7)'),
                    );
                case 'displayNotificationWarning':
                    return array(
                        'id_option' => 'displayNotificationWarning',
                        'name' => $this->l('Warning Notification (displayNotificationWarning) - (for PS 1.7)'),
                    );
                case 'displayNav1':
                    return array(
                        'id_option' => 'displayNav1',
                        'name' => $this->l('Navigation Block 1(displayNav1) - (for PS 1.7)'),
                    );
                case 'displayNav2':
                    return array(
                        'id_option' => 'displayNav2',
                        'name' => $this->l('Navigation Block 2 (displayNav2) - (for PS 1.7)'),
                    );
                case 'displayNavFullWidth':
                    return array(
                        'id_option' => 'displayNavFullWidth',
                        'name' => $this->l('Width Navigation Menu (displayNavFullWidth) - (for PS 1.7)'),
                    );
                case 'displayNotFound':
                    return array(
                        'id_option' => 'displayNotFound',
                        'name' => $this->l('404 Page (displayNotFound) - (for PS 1.7)'),
                    );
                case 'displayReassurance':
                    return array(
                        'id_option' => 'displayReassurance',
                        'name' => $this->l('Customer Reassurance Block (displayReassurance) - (for PS 1.7)'),
                    );
                case 'displayMaintenance':
                    return array(
                        'id_option' => 'displayMaintenance',
                        'name' => $this->l('Maintenance Page (displayMaintenance) - (for PS 1.7)'),
                    );
                case 'displayCMSDisputeInformation':
                    return array(
                        'id_option' => 'displayCMSDisputeInformation',
                        'name' => $this->l(
                            'CMS Dispute Information Block (displayCMSDisputeInformation) - (for PS 1.7)'
                        ),
                    );
                case 'displayStaticBlock':
                    return array(
                        'id_option' => 'displayStaticBlock',
                        'name' => $this->l('Custom Hook(displayStaticBlock)'),
                    );
            }
        }
    }

    protected function applyTemplate($static_blocks = array())
    {
        if (isset($static_blocks) && is_array($static_blocks)) {
            $idShop = $this->context->shop->id;
            $id_lang = $this->context->language->id;
            $iso_lang = $this->context->language->iso_code;
            $shop_url = Context::getContext()->link->getPageLink(
                'index',
                true,
                $id_lang,
                null,
                false,
                $idShop
            );
            $shop_name = Tools::safeOutput(
                Configuration::get('PS_SHOP_NAME', null, null, $idShop)
            );
            $shop_email = Tools::safeOutput(
                Configuration::get('PS_SHOP_EMAIL', null, null, $idShop)
            );
            foreach ($static_blocks as &$block) {
                if (!$block['id_static_block_template']) {
                    continue;
                } else {
                    $this->context->smarty->assign(array(
                        'block' => $block,
                    ));
                    $pop_up = $this->context->smarty->fetch(
                        _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/pop_up.tpl'
                    );
                    $block_template = StaticblockTemplates::getTemplateById(
                        $block['id_static_block_template'],
                        true
                    );
                    if (isset($block_template) && $block_template &&
                        !empty($block_template['code'])) {
                        $block['template'] = str_replace(
                            array(
                                '{pop_up}',
                                '{id_static_block}',
                                '{block_title}',
                                '{content}',
                                '{id_lang}',
                                '{iso_lang}',
                                '{shop_url}',
                                '{shop_name}',
                                '{shop_email}',
                            ),
                            array(
                                $pop_up,
                                $block['id_static_block'],
                                $block['block_title'],
                                $block['content'],
                                $id_lang,
                                $iso_lang,
                                $shop_url,
                                $shop_name,
                                $shop_email,
                            ),
                            $block_template['code']
                        );
                    }
                }
            }
            return $static_blocks;
        } else {
            return false;
        }
    }

    protected function applyCondition(&$static_blocks = array(), $controller = 'index')
    {
        if (!count($static_blocks)) {
            return false;
        } else {
            foreach ($static_blocks as $key => $block) {
                if (isset($block['conditions']) && $block['conditions']) {
                    $condition_result = array();
                    foreach ($block['conditions'] as $id_condition_group => $conditions) {
                        if (isset($conditions) && $conditions) {
                            foreach ($conditions as $condition) {
                                switch ($condition['type']) {
                                    case 'category':
                                        if ($controller == 'category') {
                                            $id_category = (int) Tools::getValue('id_category');
                                            if ($id_category) {
                                                $result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $condition['value'],
                                                    $id_category
                                                );
                                                $condition_result[$id_condition_group][] = $result;
                                            } else {
                                                $condition_result[$id_condition_group][] = false;
                                            }
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                    case 'page':
                                        $pages = Meta::getMetasByIdLang($this->context->language->id);
                                        if ($pages) {
                                            foreach ($pages as $page) {
                                                if ($controller == $page['page']) {
                                                    if ($page['id_meta']) {
                                                        $result = $this->applyOperator(
                                                            $condition['operator'],
                                                            $condition['value'],
                                                            $page['id_meta']
                                                        );
                                                        $condition_result[$id_condition_group][] = $result;
                                                    }
                                                }
                                            }
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                    case 'product':
                                        $id_product = (int) Tools::getValue('id_product');
                                        $id_category = (int) Tools::getValue('id_category');
                                        $category_result = false;
                                        $product_result = false;
                                        if ($id_product || $id_category) {
                                            if ($id_product) {
                                                $product_result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $condition['value'],
                                                    $id_product
                                                );
                                            }
                                            if ($id_category) {
                                                $products = StaticblockModel::getCategoryProducts($id_category);
                                                $category_result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $condition['value'],
                                                    $products
                                                );
                                            }
                                            $condition_result[$id_condition_group][] = ($product_result ||
                                                $category_result);
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                    case 'productprice':
                                    case 'manufacturer':
                                    case 'supplier':
                                    case 'quantity':
                                        if ($controller == 'product') {
                                            $id_product = (int) Tools::getValue('id_product');
                                            if ($id_product &&
                                                Validate::isLoadedObject($product = new Product($id_product))) {
                                                $manufacturer_result = false;
                                                $supplier_result = false;
                                                $product_result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $condition['value'],
                                                    $product->price
                                                );
                                                if ($condition['type'] == 'manufacturer') {
                                                    $manufacturer_result = $this->applyOperator(
                                                        $condition['operator'],
                                                        $condition['value'],
                                                        $product->id_manufacturer
                                                    );
                                                }
                                                if ($condition['type'] == 'supplier') {
                                                    $suppliers = StaticblockModel::getProductSuppliers($product->id);
                                                    $supplier_result = $this->applyOperator(
                                                        $condition['operator'],
                                                        $condition['value'],
                                                        $suppliers
                                                    );
                                                }
                                                if ($condition['type'] == 'quantity') {
                                                    $quantities = StockAvailable::getQuantityAvailableByProduct(
                                                        $id_product,
                                                        null,
                                                        $this->context->shop->id
                                                    );
                                                    $quantity_result = $this->applyOperator(
                                                        $condition['operator'],
                                                        $condition['value'],
                                                        $quantities
                                                    );
                                                }
                                                $condition_result[$id_condition_group][] = ($manufacturer_result ||
                                                    $supplier_result || $product_result || $quantity_result);
                                            } else {
                                                $condition_result[$id_condition_group][] = false;
                                            }
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                    case 'cartproduct':
                                        if (($controller == 'cart') ||
                                            (true === Tools::version_compare(_PS_VERSION_, '1.7', '<')) &&
                                            $controller == 'order') {
                                            if ($this->context->cart) {
                                                $products = array();
                                                $cart_products = $this->context->cart->getProducts();
                                                foreach ($cart_products as $product) {
                                                    $products[] = $product['id_product'];
                                                }
                                                $result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $condition['value'],
                                                    $products
                                                );
                                                $condition_result[$id_condition_group][] = $result;
                                            }
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                    case 'carttotal':
                                        if (($controller == 'cart') ||
                                            (true === Tools::version_compare(_PS_VERSION_, '1.7', '<')) &&
                                            $controller == 'order') {
                                            if ($this->context->cart) {
                                                $cartTotal = $this->context->cart->getOrderTotal();
                                                $result = $this->applyOperator(
                                                    $condition['operator'],
                                                    $cartTotal,
                                                    $condition['value']
                                                );
                                                $condition_result[$id_condition_group][] = $result;
                                            } else {
                                                $condition_result[$id_condition_group][] = false;
                                            }
                                        } else {
                                            $condition_result[$id_condition_group][] = false;
                                        }
                                        break;
                                }
                            }
                        }
                    }

                    $final_result = array();
                    if (isset($condition_result) && $condition_result) {
                        foreach ($condition_result as $result) {
                            $final_result[] = (in_array(false, $result) ? false : true);
                        }
                    }
                    if (isset($final_result) && is_array($final_result) && !in_array(true, $final_result)) {
                        unset($static_blocks[$key]);
                    }
                }
            }
            return $static_blocks;
        }
    }

    protected function applyOperator($op, $leftOperand, $rightOperand = null)
    {
        if (!$op || !$leftOperand) {
            return false;
        }

        switch ($op) {
            case 'equals':
                if (is_array($rightOperand)) {
                    return (in_array($leftOperand, $rightOperand));
                } else {
                    return ($leftOperand == $rightOperand);
                }
                break;
            case 'notequals':
                if (is_array($rightOperand)) {
                    return (!in_array($leftOperand, $rightOperand));
                } else {
                    return ($leftOperand != $rightOperand);
                }
                break;
            case 'greaterthan':
                return ($leftOperand > $rightOperand);
            case 'lessthan':
                return ($leftOperand < $rightOperand);
            default:
                return ($leftOperand == $rightOperand);
        }
    }

    protected function renderBlockList()
    {
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->fields_list = array(
            'id_static_block' => array(
                'align' => 'center',
                'title' => $this->l('ID'),
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ),
            'block_title' => array(
                'title' => $this->l('Title'),
                'width' => 200,
                'type' => 'text',
                'orderby' => true,
                'search' => false,
            ),
            'hook' => array(
                'title' => $this->l('Hook'),
                'width' => 80,
                'type' => 'text',
                'callback' => 'getListHook',
                'callback_object' => $this,
                'orderby' => true,
                'search' => false,
            ),
            'status' => array(
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'active' => 'status',
                'type' => 'bool',
                'filter_type' => 'int',
                'search' => false,
            ),
            'title_active' => array(
                'align' => 'center',
                'title' => $this->l('Show Title'),
                'width' => 20,
                'active' => 'title_active',
                'type' => 'bool',
                'filter_type' => 'int',
                'search' => false,
            ),
            'date_add' => array(
                'align' => 'right',
                'title' => $this->l('Date Created'),
                'width' => 70,
                'type' => 'text',
                'search' => false,
            ),
            'date_upd' => array(
                'align' => 'right',
                'title' => $this->l('Last Modified'),
                'width' => 70,
                'type' => 'text',
                'search' => false,
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'position' => 'position',
                'class' => 'fixed-width-md',
                'search' => false,
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->module = $this;
        $helper->simple_header = false;
        $helper->table_id = 'module-' . $this->name;
        $helper->identifier = 'id_static_block';
        $helper->position_identifier = $helper->identifier;
        $helper->actions = array('edit', 'delete');
        $helper->bulk_actions = true;
        $helper->show_toolbar = true;
        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->orderBy = 'position';
        $helper->orderWay = 'ASC';
        $helper->listTotal = StaticblockModel::countListContent();
        $helper->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
        $helper->toolbar_btn = array(
            'new' => array(
                'href' => AdminController::$currentIndex . '&configure=' . $this->name .
                '&add' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Add New'),
            ),
        );
        $this->context->smarty->assign(
            array(
                'action_url' => $helper->currentIndex . '&token=' . $helper->token,
            )
        );
        return $helper->generateList(
            StaticblockModel::getDetail($this->context->employee->id_lang),
            $this->fields_list
        );
    }

    protected function renderTemplateList()
    {
        $this->fields_list = array(
            'id_static_block_template' => array(
                'align' => 'center',
                'title' => $this->l('ID'),
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ),
            'template_name' => array(
                'title' => $this->l('Template'),
                'width' => 200,
                'type' => 'text',
                'orderby' => true,
                'search' => false,
            ),
            'status' => array(
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'active' => 'status',
                'type' => 'bool',
                'filter_type' => 'int',
                'search' => false,
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->module = $this;
        $helper->simple_header = false;
        $helper->table_id = 'module-' . $this->name;
        $helper->identifier = 'id_static_block_template';
        $helper->position_identifier = $helper->identifier;
        $helper->actions = array('edit', 'delete');
        $helper->bulk_actions = true;
        $helper->show_toolbar = true;
        $helper->title = $this->l('Block Templates');
        $helper->table = $this->name . '_templates';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->orderBy = $helper->identifier;
        $helper->orderWay = 'ASC';
        $helper->listTotal = StaticblockTemplates::countListContent();
        $helper->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
        $helper->toolbar_btn = array(
            'new' => array(
                'href' => AdminController::$currentIndex . '&configure=' . $this->name .
                '&add' . $this->name . '_template&token=' .
                Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Add New'),
            ),
        );
        $this->context->smarty->assign(
            array(
                'action_url' => $helper->currentIndex . '&token=' . $helper->token,
            )
        );
        return $helper->generateList(
            StaticblockTemplates::getTemplates(),
            $this->fields_list
        );
    }

    protected function renderCustomhook()
    {
        $all_data = StaticblockModel::getAllHooks();
        $admin_url = AdminController::$currentIndex . '&configure=' . $this->name.'&add' . $this->name . '_customhook&token='.Tools::getAdminTokenLite('AdminModules');
        $delete_rec = AdminController::$currentIndex . '&configure=' . $this->name.'&delete' . $this->name . '_customhook&token='.Tools::getAdminTokenLite('AdminModules');
        $this->context->smarty->assign(array(
            'admin_url' => $admin_url,
            'delete_rec' => $delete_rec,
            'all_data' => $all_data,
        ));
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/customhook.tpl'
        );
    }
    
    protected function renderReassuranceSetting()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $animations = array(
            array(
                'id_option' => 1,
                'name' => $this->l('Hover'),
            ),
            array(
                'id_option' => 2,
                'name' => $this->l('Collapse'),
            ),
            array(
                'id_option' => 3,
                'name' => $this->l('Hover2'),
            ),
            array(
                'id_option' => 4,
                'name' => $this->l('Tooltip'),
            ),
        );
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Reassurance Block Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'col' => 4,
                    'label' => $this->l('Display Animation Home'),
                    'name' => 'REASSURANCE_HOME_ANIMATION',
                    'options' => array(
                        'query' => $animations,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'col' => 4,
                    'label' => $this->l('Display Animation Footer'),
                    'name' => 'REASSURANCE_FOOTER_ANIMATION',
                    'options' => array(
                        'query' => $animations,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'col' => 4,
                    'label' => $this->l('Dsiplay Animation Product'),
                    'name' => 'REASSURANCE_PRODUCT_ANIMATION',
                    'options' => array(
                        'query' => $animations,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submit' . $this->name . '_rsetting',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'icon' => 'icon-tint',
                'title' => $this->l('Pickup Collapse Animation Colors'),
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Title Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Sub Title Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_SUB_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Description Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_DESCRIPTION_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_HOVER_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_BACKGROUND_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Link Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_LINK_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Arrow Color'),
                    'name' => 'color_theme[REASSURANCE_COLLAPSE_ARROW_COLOR]',
                ),
            ),

            'submit' => array(
                'name' => 'submit' . $this->name . '_rsetting',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $this->fields_form[2]['form'] = array(
            'legend' => array(
                'icon' => 'icon-tint',
                'title' => $this->l('Pickup Tooltip Animation Colors'),
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Title Color'),
                    'name' => 'color_theme[REASSURANCE_TOOLTIP_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Sub Title Color'),
                    'name' => 'color_theme[REASSURANCE_TOOLTIP_SUB_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'color_theme[REASSURANCE_TOOLTIP_BACKGROUND_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Description Color'),
                    'name' => 'color_theme[REASSURANCE_TOOLTIP_DESCRIPTION_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Hover Color'),
                    'name' => 'color_theme[REASSURANCE_TOOLTIP_BACK_HOVER_COLOR]',
                ),
            ),

            'submit' => array(
                'name' => 'submit' . $this->name . '_rsetting',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $this->fields_form[3]['form'] = array(
            'legend' => array(
                'icon' => 'icon-tint',
                'title' => $this->l('Pickup Hover Animation Colors'),
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Title Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Description Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER_SUB_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER_BACKGROUND_COLOR]',
                ),
            ),
            'submit' => array(
                'name' => 'submit' . $this->name . '_rsetting',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $this->fields_form[4]['form'] = array(
            'legend' => array(
                'icon' => 'icon-tint',
                'title' => $this->l('Pickup Hover2 Animation Colors'),
            ),
            'input' => array(
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER2_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Title Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER2_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Description Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER2_SUB_TITLE_COLOR]',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'color_theme[REASSURANCE_HOVER2_BACKGROUND_COLOR]',
                ),
            ),

            'submit' => array(
                'name' => 'submit' . $this->name . '_rsetting',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->table = 'configuration';
        $helper->identifier = 'id_configuration';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_toolbar = true;
        $helper->show_cancel_button = false;
        $helper->submit_action = 'submit' . $this->name . '_rsetting';
        $helper->back_url = AdminController::$currentIndex . '&configure=' .
        $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules');
        $home = Configuration::get(
            'REASSURANCE_HOME_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $footer = Configuration::get(
            'REASSURANCE_FOOTER_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $product = Configuration::get(
            'REASSURANCE_PRODUCT_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        /* Get Animation settings values */
        $c_title = Configuration::get(
            'REASSURANCE_COLLAPSE_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_sub_title = Configuration::get(
            'REASSURANCE_COLLAPSE_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_desc = Configuration::get(
            'REASSURANCE_COLLAPSE_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_hover = Configuration::get(
            'REASSURANCE_COLLAPSE_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_back = Configuration::get(
            'REASSURANCE_COLLAPSE_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_link = Configuration::get(
            'REASSURANCE_COLLAPSE_LINK_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_arrow = Configuration::get(
            'REASSURANCE_COLLAPSE_ARROW_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_title = Configuration::get(
            'REASSURANCE_TOOLTIP_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_sub_title = Configuration::get(
            'REASSURANCE_TOOLTIP_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_desc = Configuration::get(
            'REASSURANCE_TOOLTIP_DESCRIPTION_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_back = Configuration::get(
            'REASSURANCE_TOOLTIP_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_back_hover = Configuration::get(
            'REASSURANCE_TOOLTIP_BACK_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_title = Configuration::get(
            'REASSURANCE_HOVER_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_sub_title = Configuration::get(
            'REASSURANCE_HOVER_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_hover = Configuration::get(
            'REASSURANCE_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_back_hover = Configuration::get(
            'REASSURANCE_HOVER_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_title = Configuration::get(
            'REASSURANCE_HOVER2_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_sub_title = Configuration::get(
            'REASSURANCE_HOVER2_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_hover = Configuration::get(
            'REASSURANCE_HOVER2_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_back_hover = Configuration::get(
            'REASSURANCE_HOVER2_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $helper->fields_value['REASSURANCE_HOME_ANIMATION'] = $home;
        $helper->fields_value['REASSURANCE_FOOTER_ANIMATION'] = $footer;
        $helper->fields_value['REASSURANCE_PRODUCT_ANIMATION'] = $product;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_TITLE_COLOR]'] = $c_title;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_SUB_TITLE_COLOR]'] = $c_sub_title;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_DESCRIPTION_COLOR]'] = $c_desc;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_HOVER_COLOR]'] = $c_hover;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_BACKGROUND_COLOR]'] = $c_back;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_LINK_COLOR]'] = $c_link;
        $helper->fields_value['color_theme[REASSURANCE_COLLAPSE_ARROW_COLOR]'] = $c_arrow;
        $helper->fields_value['color_theme[REASSURANCE_TOOLTIP_TITLE_COLOR]'] = $t_title;
        $helper->fields_value['color_theme[REASSURANCE_TOOLTIP_SUB_TITLE_COLOR]'] = $t_sub_title;
        $helper->fields_value['color_theme[REASSURANCE_TOOLTIP_DESCRIPTION_COLOR]'] = $t_desc;
        $helper->fields_value['color_theme[REASSURANCE_TOOLTIP_BACKGROUND_COLOR]'] = $t_back;
        $helper->fields_value['color_theme[REASSURANCE_TOOLTIP_BACK_HOVER_COLOR]'] = $t_back_hover;
        $helper->fields_value['color_theme[REASSURANCE_HOVER_TITLE_COLOR]'] = $h_title;
        $helper->fields_value['color_theme[REASSURANCE_HOVER_SUB_TITLE_COLOR]'] = $h_sub_title;
        $helper->fields_value['color_theme[REASSURANCE_HOVER_COLOR]'] = $h_hover;
        $helper->fields_value['color_theme[REASSURANCE_HOVER_BACKGROUND_COLOR]'] = $h_back_hover;
        $helper->fields_value['color_theme[REASSURANCE_HOVER2_TITLE_COLOR]'] = $h2_title;
        $helper->fields_value['color_theme[REASSURANCE_HOVER2_SUB_TITLE_COLOR]'] = $h2_sub_title;
        $helper->fields_value['color_theme[REASSURANCE_HOVER2_COLOR]'] = $h2_hover;
        $helper->fields_value['color_theme[REASSURANCE_HOVER2_BACKGROUND_COLOR]'] = $h2_back_hover;
        $this->context->smarty->assign(
            array(
                'right_column' => false,
                'codemirror' => false,
            )
        );
        return $helper->generateForm($this->fields_form);
    }

    protected function renderReassuranceList()
    {
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->fields_list = array(
            'id_fmm_reassurance' => array(
                'align' => 'center',
                'title' => $this->l('ID'),
                'width' => 30,
                'type' => 'text',
                'search' => false,
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'width' => 20,
                'type' => 'text',
                'orderby' => true,
                'search' => false,
            ),
            'sub_title' => array(
                'align' => 'center',
                'title' => $this->l('Sub Title'),
                'type' => 'text',
                'orderby' => true,
                'search' => false,
            ),
            'status' => array(
                'align' => 'center',
                'title' => $this->l('Status'),
                'width' => 20,
                'active' => 'status',
                'type' => 'bool',
                'filter_type' => 'int',
                'search' => false,
            ),
            'apperance' => array(
                'align' => 'center',
                'title' => $this->l('Appearance'),
                'width' => 20,
                'type' => 'text',
                'search' => false,
            ),
            'link' => array(
                'align' => 'center',
                'title' => $this->l('Link'),
                'width' => 20,
                'type' => 'text',
                'search' => false,
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->module = $this;
        $helper->simple_header = false;
        $helper->table_id = 'module-' . $this->name;
        $helper->identifier = 'id_fmm_reassurance';
        $helper->position_identifier = $helper->identifier;
        $helper->actions = array('edit', 'delete');
        $helper->bulk_actions = true;
        $helper->show_toolbar = true;
        $helper->title = $this->l('Reassurance Block');
        $helper->table = $this->name . '_reassuranceblock';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->orderBy = $helper->identifier;
        $helper->orderWay = 'ASC';
        $helper->listTotal = StaticReassuranceModel::countListContent();
        $helper->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
        $helper->toolbar_btn = array(
            'new' => array(
                'href' => AdminController::$currentIndex . '&configure=' . $this->name .
                '&add' . $this->name . '_reassuranceblock&token=' .
                Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Add New'),
            ),
        );
        $this->context->smarty->assign(
            array(
                'action_url' => $helper->currentIndex . '&token=' . $helper->token,
            )
        );
        return $helper->generateList(
            StaticReassuranceModel::getReassuranceBlock($this->context->employee->id_lang),
            $this->fields_list
        );
    }

    protected function renderSettingsForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $options = array();
        foreach ($this->editor_themes as $theme) {
            $options[] = array(
                'id_option' => $theme,
                'name' => $theme,
            );
        }
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'col' => 4,
                    'label' => $this->l('Code Editor Theme'),
                    'name' => 'STATIC_BLOCK_EDITOR_THEME',
                    'options' => array(
                        'query' => $options,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'saveSettings',
                'title' => $this->l('Save Settings'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->table = 'configuration';
        $helper->identifier = 'id_configuration';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_toolbar = true;
        $helper->show_cancel_button = false;
        $helper->submit_action = 'saveSettings';
        $helper->back_url = AdminController::$currentIndex . '&configure=' . $this->name .
        '&token=' . Tools::getAdminTokenLite('AdminModules');

        $theme = (Configuration::get('STATIC_BLOCK_EDITOR_THEME') ?
            Configuration::get('STATIC_BLOCK_EDITOR_THEME') : 'monokai');
        $helper->fields_value['STATIC_BLOCK_EDITOR_THEME'] = $theme;
        $this->context->smarty->assign(array('right_column' => false, 'codemirror' => false));

        $this->context->controller->addjQueryPlugin(array(
            'select2',
        ));
        $this->context->controller->addJS(array(
            _PS_JS_DIR_ . 'jquery/plugins/select2/select2_locale_' . $this->context->language->iso_code . '.js',
        ));
        return $helper->generateForm($this->fields_form);
    }

    protected function renderBlockForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $id_static_block = (int) Tools::getValue('id_static_block');
        $radio = (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '>=')) ? 'switch' : 'radio';

        $templates = StaticblockTemplates::getTemplates(true);
        $options = array(
            array(
                'id_option' => 0,
                'name' => $this->l('None'),
            ),
        );
        $editors = array(
            array(
                'id_option' => 1,
                'name' => $this->l('Plain Text(No Editor)'),
            ),
            array(
                'id_option' => 2,
                'name' => $this->l('Basic WYSIWYG Editor'),
            ),
            array(
                'id_option' => 3,
                'name' => $this->l('Advance Editor'),
            ),
            array(
                'id_option' => 4,
                'name' => $this->l('Code Editor'),
            ),
        );
        if (isset($templates) && $templates) {
            foreach ($templates as $template) {
                array_push(
                    $options,
                    array(
                        'id_option' => $template['id_static_block_template'],
                        'name' => $template['template_name'],
                    )
                );
            }
        }
        $this->fields_form[0]['form'] = array(
            'tinymce' => true,
            'class' => 'col-lg-10',
            'legend' => array(
                'title' => ($id_static_block) ? $this->l('Edit Static Block :') : $this->l('Add Static Block :'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_static_block',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Block Title :'),
                    'name' => 'block_title',
                    'desc' => $this->l('Please enter block title.'),
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'lang' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select editor :'),
                    'name' => 'editor',
                    'id' => 'select-editor',
                    'col' => '4',
                    'desc' => $this->l('Select Editor to add content.'),
                    'options' => array(
                        'query' => $editors,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content :'),
                    'lang' => true,
                    'class' => 'classic-editor',
                    'autoload_rte' => true,
                    'name' => 'content1',
                    'cols' => 50,
                    'rows' => 20,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content :'),
                    'lang' => true,
                    'class' => 'basic-editor',
                    //'autoload_rte' => true,
                    'name' => 'content2',
                    'cols' => 50,
                    'rows' => 20,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content :'),
                    'lang' => true,
                    'class' => 'advance-editor',
                    //'autoload_rte' => true,
                    'name' => 'content3',
                    'cols' => 50,
                    'rows' => 20,
                ),
                array(
                    'type' => 'textarea',
                    'col' => 9,
                    'class' => 'code-editor',
                    'id' => 'codes-editor',
                    'label' => $this->l('Content:'),
                    'desc' => $this->l('Enter CSS and HTML code.'),
                    'name' => 'content4',
                    'cols' => 50,
                    'rows' => 20,
                    'lang' => true,
                ),
                array(
                    'type' => 'select',
                    'name' => 'hook',
                    'required' => true,
                    'col' => 7,
                    'label' => $this->l('Hook :'),
                    'desc' => $this->l('Select a hook from the list.'),
                    'options' => array(
                        'optiongroup' => array(
                            'query' => $this->getHooks(),
                            'label' => 'label',
                        ),
                        'options' => array(
                            'query' => 'available_hooks',
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('Please select a hook'),
                        ),
                    ),
                ),
                array(
                    'type' => 'date',
                    'label' => $this->l('Date From :'),
                    'name' => 'date_from',
                    'desc' => $this->l('Your block will be shown from selected date.'),
                ),
                array(
                    'type' => 'date',
                    'label' => $this->l('Date To :'),
                    'name' => 'date_to',
                    'desc' => $this->l('Your block will be shown till selected date.'),
                ),
                array(
                    'type' => 'select',
                    'name' => 'id_static_block_template',
                    'col' => 7,
                    'label' => $this->l('Template :'),
                    'desc' => $this->l('Select a custom template from the list.'),
                    'options' => array(
                        'query' => $options,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'group',
                    'label' => $this->l('Group access'),
                    'name' => 'groupBox',
                    'values' => Group::getGroups(Context::getContext()->language->id),
                    'info_introduction' => $this->l('You now have three default customer groups.'),
                    'hint' => $this->l('Mark all of the customer groups which you would like to show this block.'),
                ),
                array(
                    'type' => $radio,
                    'name' => 'status',
                    'class' => 't',
                    'is_bool' => true,
                    'label' => $this->l('Status :'),
                    'desc' => $this->l('Enable/Disable a block.'),
                    'values' => array(
                        array(
                            'id' => 'status_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'status_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => $radio,
                    'class' => 't',
                    'is_bool' => true,
                    'label' => $this->l('Show Title :'),
                    'desc' => $this->l('Enable/Disable block title.'),
                    'name' => 'title_active',
                    'values' => array(
                        array(
                            'id' => 'title_active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'title_active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => $radio,
                    'class' => 't',
                    'is_bool' => true,
                    'label' => $this->l('Custom CSS :'),
                    'desc' => $this->l('Enable/Disable custom css (if disabled default css will be apply).'),
                    'name' => 'custom_css',
                    'values' => array(
                        array(
                            'id' => 'custom_css_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'custom_css_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom CSS Code :'),
                    'desc' => $this->l('You can enter your own css code here (optional).'),
                    'name' => 'css',
                    'cols' => 20,
                    'rows' => 30,
                ),
            ),
            'submit' => array(
                'name' => 'submit' . $this->name,
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default pull-right',
            ),
        );

        if (Shop::isFeatureActive()) {
            array_push($this->fields_form[0]['form']['input'], array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                'class' => 'id_shop',
            ));
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->table = 'static_block';
        $helper->identifier = 'id_static_block';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_toolbar = true;
        $helper->show_cancel_button = true;
        $helper->submit_action = 'save' . $this->name;
        $helper->title = $this->l('Add/Edit Static Block.');
        $helper->back_url = AdminController::$currentIndex . '&configure=' . $this->name .
        '&token=' . Tools::getAdminTokenLite('AdminModules') . '&currentTab=blocks';
        $languages = Language::getLanguages(true);
        foreach ($languages as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
            );
        }

        $category_groups_ids = array();
        if ($id_static_block) {
            $model = new StaticblockModel((int) $id_static_block);
            $category_groups_ids = $model->getGroups();
            $helper->currentIndex = AdminController::$currentIndex . '&configure=' .
            $this->name . '&edit' . $this->name . '&id_static_block=' . $id_static_block;
            $block = StaticblockModel::getBlockById($id_static_block);
            foreach ($languages as $lang) {
                $helper->fields_value['block_title'][$lang['id_lang']] = $block['block_title'][$lang['id_lang']];
                $helper->fields_value['content1'][$lang['id_lang']] = $block['content'][$lang['id_lang']];
                $helper->fields_value['content2'][$lang['id_lang']] = $block['content'][$lang['id_lang']];
                $helper->fields_value['content3'][$lang['id_lang']] = $block['content'][$lang['id_lang']];
                $helper->fields_value['content4'][$lang['id_lang']] = $block['content'][$lang['id_lang']];
            }
            $helper->fields_value['id_static_block'] = $block['id_static_block'];
            $helper->fields_value['id_static_block_template'] = $block['id_static_block_template'];
            $helper->fields_value['status'] = $block['status'];
            $helper->fields_value['custom_css'] = $block['custom_css'];
            $helper->fields_value['hook'] = $block['hook'];
            $helper->fields_value['editor'] = $block['editor'];
            $helper->fields_value['title_active'] = $block['title_active'];
            $helper->fields_value['css'] = $block['css'];
            $helper->fields_value['date_to'] = $block['date_to'];
            $helper->fields_value['date_from'] = $block['date_from'];
        } else {
            foreach ($languages as $lang) {
                $helper->fields_value['block_title'] = Tools::getValue('block_title');
                $helper->fields_value['content1'] = Tools::getValue('content1');
                $helper->fields_value['content2'] = Tools::getValue('content2');
                $helper->fields_value['content3'] = Tools::getValue('content3');
                $helper->fields_value['content4'] = Tools::getValue('content4');
            }
            $helper->fields_value['id_static_block_template'] = (int) Tools::getValue('id_static_block_template');
            $helper->fields_value['id_static_block'] = (int) Tools::getValue('id_static_block');
            $helper->fields_value['status'] = (int) Tools::getValue('status');
            $helper->fields_value['custom_css'] = (int) Tools::getValue('custom_css');
            $helper->fields_value['hook'] = (string) Tools::getValue('hook');
            $helper->fields_value['editor'] = (int) Tools::getValue('editor');
            $helper->fields_value['title_active'] = (int) Tools::getValue('title_active');
            $helper->fields_value['css'] = Tools::getValue('css');
            $helper->fields_value['date_to'] = Tools::getValue('date_to');
            $helper->fields_value['date_from'] = Tools::getValue('date_from');
        }
        $this->context->controller->addjQueryPlugin(array('date'));
        $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/plugins/timepicker/jquery-ui-timepicker-addon.css');
        $products = Product::getProducts(
            $this->context->language->id,
            0,
            9999,
            'pl.name',
            'ASC',
            false,
            true
        );
        $this->context->smarty->assign(array(
            'manufacturers' => Manufacturer::getManufacturers(),
            'suppliers' => Supplier::getSuppliers(),
            'categories' => Category::getSimpleCategories((int) $this->context->language->id),
            'conditions' => StaticblockModel::getConditions($id_static_block),
            'is_multishop' => Shop::isFeatureActive(),
            'products' => $products,
            'pages' => Meta::getMetasByIdLang($this->context->language->id),
        ));
        // Added values of object Group
        $groups = Group::getGroups($this->context->language->id);
        // if empty $carrier_groups_ids : object creation : we set the default groups
        if (empty($category_groups_ids)) {
            $preselected = array(
                Configuration::get('PS_UNIDENTIFIED_GROUP'),
                Configuration::get('PS_GUEST_GROUP'),
                Configuration::get('PS_CUSTOMER_GROUP'),
            );
            $category_groups_ids = array_merge($category_groups_ids, $preselected);
        }
        foreach ($groups as $group) {
            $helper->fields_value['groupBox_' . $group['id_group']] = Tools::getValue(
                'groupBox_' . $group['id_group'],
                (
                    in_array(
                        $group['id_group'],
                        $category_groups_ids
                    )
                )
            );
        }

        $this->context->controller->addjQueryPlugin(array(
            'select2',
        ));
        $this->context->controller->addJS(array(
            _PS_JS_DIR_ . 'jquery/plugins/select2/select2_locale_' . $this->context->language->iso_code . '.js',
        ));
        $theme = (Configuration::get('STATIC_BLOCK_EDITOR_THEME') ?
            Configuration::get('STATIC_BLOCK_EDITOR_THEME') : 'monokai');
        $langs = Language::getLanguages(true);
        $this->context->smarty->assign(array(
            'right_column' => true,
            'codemirrors' => true,
            'codemirror' => false,
            'theme' => $theme,
            'langs' => json_encode($langs),
        ));
        $this->context->controller->addJs(array(
            $this->_path . 'views/js/codemirror/codemirror.js',
            $this->_path . 'views/js/editor/summernote.js',
            $this->_path . 'views/js/codemirror/mode/xml.js',
            $this->_path . 'views/js/codemirror/mode/css.js',
            $this->_path . 'views/js/codemirror/mode/htmlmixed.js',
            $this->_path . 'views/js/codemirror/mode/javascript.js',
            $this->_path . 'views/js/codemirror/mode/smarty.js',
            $this->_path . 'views/js/codemirror/mode/smartymixed.js',
            $this->_path . 'views/js/codemirror/addon/show-hint.js',
            $this->_path . 'views/js/codemirror/addon/xml-hint.js',
            $this->_path . 'views/js/codemirror/addon/css-hint.js',
            $this->_path . 'views/js/codemirror/addon/html-hint.js',
        ));
        $this->context->controller->addCss(array(
            $this->_path . 'views/css/codemirror/codemirror.min.css',
            $this->_path . 'views/css/editor/summernote.css',
            $this->_path . 'views/css/codemirror/theme/' . $theme . '.css',
        ));
        return $helper->generateForm($this->fields_form);
    }

    protected function renderTemplateForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $id_static_block_template = (int) Tools::getValue('id_static_block_template');
        $radio = (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '>=')) ? 'switch' : 'radio';
        $this->fields_form[0]['form'] = array(
            'class' => 'col-lg-10',
            'legend' => array(
                'title' => ($id_static_block_template) ? $this->l('Edit Template') : $this->l('Add Template'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_static_block_template',
                ),
                array(
                    'type' => 'text',
                    'col' => 6,
                    'label' => $this->l('Template Name'),
                    'name' => 'template_name',
                    'desc' => $this->l('Internal template name.'),
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => $radio,
                    'class' => 't',
                    'is_bool' => true,
                    'label' => $this->l('Status:'),
                    'name' => 'status',
                    'values' => array(
                        array(
                            'id' => 'status_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'status_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => 'textarea',
                    'col' => 9,
                    'id' => 'template-code',
                    //'class' => 'autoload_rte rte',
                    'label' => $this->l('Template Code :'),
                    'desc' => $this->l('Enter CSS and HTML code.'),
                    'name' => 'code',
                    'cols' => 30,
                    'rows' => 50,
                ),
                array(
                    'type' => 'html',
                    'name' => 'extra_variables',
                    'html_content' => $this->context->smarty->fetch(
                        _PS_MODULE_DIR_ . $this->name . '/views/templates/admin/extra_variables.tpl'
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submit' . $this->name . '_template',
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->table = 'static_block_template';
        $helper->identifier = 'id_static_block_template';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_toolbar = true;
        $helper->show_cancel_button = true;
        $helper->submit_action = 'save' . $this->name . '_template';
        $helper->title = $this->l('Add/Edit Template.');
        $helper->back_url = AdminController::$currentIndex . '&configure=' . $this->name .
        '&token=' . Tools::getAdminTokenLite('AdminModules') . '&currentTab=templates';
        if ($id_static_block_template) {
            $template = StaticblockTemplates::getTemplateById($id_static_block_template);
            $helper->fields_value['id_static_block_template'] = $template['id_static_block_template'];
            $helper->fields_value['status'] = $template['status'];
            $helper->fields_value['code'] = $template['code'];
            $helper->fields_value['template_name'] = $template['template_name'];
        } else {
            $helper->fields_value['id_static_block_template'] = (int) Tools::getValue('id_static_block_template');
            $helper->fields_value['status'] = (int) Tools::getValue('status');
            $helper->fields_value['code'] = Tools::getValue('code');
            $helper->fields_value['template_name'] = Tools::getValue('template_name');
        }
        $theme = (Configuration::get('STATIC_BLOCK_EDITOR_THEME') ?
            Configuration::get('STATIC_BLOCK_EDITOR_THEME') : 'monokai');
        $this->context->smarty->assign(array(
            'right_column' => false,
            'codemirror' => true,
            'codemirrors' => false,
            'theme' => $theme,
        ));

        $this->context->controller->addJs(array(
            $this->_path . 'views/js/codemirror/codemirror.js',
            $this->_path . 'views/js/codemirror/mode/xml.js',
            $this->_path . 'views/js/codemirror/mode/css.js',
            $this->_path . 'views/js/codemirror/mode/htmlmixed.js',
            $this->_path . 'views/js/codemirror/mode/javascript.js',
            $this->_path . 'views/js/codemirror/mode/smarty.js',
            $this->_path . 'views/js/codemirror/mode/smartymixed.js',
            $this->_path . 'views/js/codemirror/addon/show-hint.js',
            $this->_path . 'views/js/codemirror/addon/xml-hint.js',
            $this->_path . 'views/js/codemirror/addon/css-hint.js',
            $this->_path . 'views/js/codemirror/addon/html-hint.js',
        ));
        $this->context->controller->addCss(array(
            $this->_path . 'views/css/codemirror/codemirror.min.css',
            $this->_path . 'views/css/codemirror/theme/' . $theme . '.css',
        ));
        return $helper->generateForm($this->fields_form);
    }

    protected function renderReassuranceForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $id_fmm_reassurance = (int) Tools::getValue('id_fmm_reassurance');
        $radio = (Tools::version_compare(_PS_VERSION_, '1.6.0.0', '>=')) ? 'switch' : 'radio';
        $apperance = array(
            array(
                'id_option' => 'home',
                'name' => $this->l('Display Home'),
            ),
            array(
                'id_option' => 'footer',
                'name' => $this->l('Display Footer'),
            ),
            array(
                'id_option' => 'product-footer',
                'name' => $this->l('Product Footer'),
            ),
        );
        $this->fields_form[0]['form'] = array(
            'tinymce' => true,
            'class' => 'col-lg-10',
            'legend' => array(
                'title' => ($id_fmm_reassurance) ?
                $this->l('Edit Reassurance Block') : $this->l('Add Reassurance Block'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_fmm_reassurance',
                ),
                array(
                    'type' => $radio,
                    'class' => 't',
                    'is_bool' => true,
                    'label' => $this->l('Status:'),
                    'name' => 'status',
                    'values' => array(
                        array(
                            'id' => 'status_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'status_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => 'text',
                    'col' => 6,
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'lang' => true,
                    'desc' => $this->l('Title for block reassurance'),
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'col' => 6,
                    'label' => $this->l('Sub Title'),
                    'name' => 'sub_title',
                    'lang' => true,
                    'desc' => $this->l('Sub Title for block reassurance'),
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'description',
                    'lang' => true,
                    'col' => 7,
                    'autoload_rte' => true,
                    'label' => $this->l('Description'),
                    'desc' => $this->l('Enter description for reassurance block'),
                ),
                array(
                    'type' => 'text',
                    'col' => 5,
                    'label' => $this->l('Link'),
                    'name' => 'link',
                    'desc' => $this->l('Insert the link where it will take you on clicking the reassurance.'),
                    'required' => true,
                ),
                array(
                    'type' => 'select',
                    'name' => 'apperance',
                    'col' => 7,
                    'label' => $this->l('Apperance'),
                    'desc' => $this->l('Select hook to display this block'),
                    'options' => array(
                        'query' => $apperance,
                        'id' => 'id_option',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Upload Image:'),
                    'display_image' => true,
                    'desc' => $this->l('Select an image to be displayed for reassurance block.'),
                    'name' => 'image',
                    //'image' => $image_url ? $image_url : false,
                    'id' => 'static_block_reassurance_image',
                    'value' => true,
                ),
                array(
                    'label' => $this->l('Image Preview'),
                    'type' => 'image',
                    'name' => '',
                    'form_group_class' => 'image_preview hidden',
                ),
            ),
            'submit' => array(
                'name' => 'save' . $this->name . '_reassuranceblock',
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default pull-right',
            ),
        );
        if (Shop::isFeatureActive()) {
            array_push($this->fields_form[0]['form']['input'], array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                'class' => 'id_shop',
            ));
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->table = 'fmm_reassurance';
        $helper->identifier = 'id_fmm_reassurance';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_toolbar = true;
        $helper->show_cancel_button = true;
        $helper->submit_action = 'save' . $this->name . '_reassuranceblock';
        $helper->title = $this->l('Add/Edit Template.');
        $helper->back_url = AdminController::$currentIndex . '&configure=' .
        $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') .
            '&currentTab=reassurance';
        $languages = Language::getLanguages(true);
        foreach ($languages as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
            );
        }
        if ($id_fmm_reassurance) {
            $reassurance = StaticReassuranceModel::getReassuranceBlockById($id_fmm_reassurance);
            foreach ($languages as $lang) {
                $helper->fields_value['title'][$lang['id_lang']] = $reassurance['title'][$lang['id_lang']];
                $helper->fields_value['sub_title'][$lang['id_lang']] = $reassurance['sub_title'][$lang['id_lang']];
                $helper->fields_value['description'][$lang['id_lang']] = $reassurance['description'][$lang['id_lang']];
            }
            $image_url = _PS_IMG_ . 'sbr/' . $reassurance['image'];
            $helper->fields_value['id_fmm_reassurance'] = $reassurance['id_fmm_reassurance'];
            $helper->fields_value['status'] = $reassurance['status'];
            $helper->fields_value['image'] = $reassurance['image'];
            $helper->fields_value['link'] = $reassurance['link'];
            $helper->fields_value['apperance'] = $reassurance['apperance'];
            //$helper->fields_value['position'] = $reassurance['position'];
            $this->context->smarty->assign('img_src', $image_url);
            $this->context->smarty->fetch(dirname(__FILE__) . '/views/templates/admin/preview.tpl');
        } else {
            foreach ($languages as $lang) {
                $helper->fields_value['title'] = Tools::getValue('title');
                $helper->fields_value['sub_title'] = Tools::getValue('sub_title');
                $helper->fields_value['description'] = Tools::getValue('description');
            }
            $helper->fields_value['id_fmm_reassurance'] = (int) Tools::getValue('id_fmm_reassurance');
            $helper->fields_value['status'] = (bool) Tools::getValue('status');
            $helper->fields_value['image'] = pSQL(Tools::getValue('image'));
            $helper->fields_value['link'] = pSQL(Tools::getValue('link'));
            $helper->fields_value['apperance'] = pSQL(Tools::getValue('apperance'));
            //$helper->fields_value['position'] = (int) Tools::getValue('position');
        }
        $this->context->controller->addJs(array(
            $this->_path . 'views/js/preview.js',
        ));
        $this->context->smarty->assign(array(
            'right_column' => false,
            'codemirror' => false,
            'codemirrors' => false,
        ));
        return $helper->generateForm($this->fields_form);
    }

    public function hookTop($params)
    {
        $hookBlocks = $this->getHookBlocks('top');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayHome($params)
    {
        $reassurance = $this->reassuranceBlock('home');
        //var_dump($reassurance);die;
        $hookBlocks = $this->getHookBlocks('home');
        return $this->getBlock($hookBlocks, $params) . $reassurance;
    }

    public function reassuranceBlock($apperance)
    {
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $force_ssl = (Configuration::get('PS_SSL_ENABLED') &&
            Configuration::get('PS_SSL_ENABLED_EVERYWHERE')
        );
        $home = Configuration::get(
            'REASSURANCE_HOME_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $footer = Configuration::get(
            'REASSURANCE_FOOTER_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $product = Configuration::get(
            'REASSURANCE_PRODUCT_ANIMATION',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        /* Get Animation settings values */
        $c_title = Configuration::get(
            'REASSURANCE_COLLAPSE_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_sub_title = Configuration::get(
            'REASSURANCE_COLLAPSE_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_desc = Configuration::get(
            'REASSURANCE_COLLAPSE_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_hover = Configuration::get(
            'REASSURANCE_COLLAPSE_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_back = Configuration::get(
            'REASSURANCE_COLLAPSE_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_link = Configuration::get(
            'REASSURANCE_COLLAPSE_LINK_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $c_arrow = Configuration::get(
            'REASSURANCE_COLLAPSE_ARROW_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_title = Configuration::get(
            'REASSURANCE_TOOLTIP_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_sub_title = Configuration::get(
            'REASSURANCE_TOOLTIP_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_desc = Configuration::get(
            'REASSURANCE_TOOLTIP_DESCRIPTION_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_back = Configuration::get(
            'REASSURANCE_TOOLTIP_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $t_back_hover = Configuration::get(
            'REASSURANCE_TOOLTIP_BACK_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_title = Configuration::get(
            'REASSURANCE_HOVER_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_sub_title = Configuration::get(
            'REASSURANCE_HOVER_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_hover = Configuration::get(
            'REASSURANCE_HOVER_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h_back_hover = Configuration::get(
            'REASSURANCE_HOVER_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_title = Configuration::get(
            'REASSURANCE_HOVER2_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_sub_title = Configuration::get(
            'REASSURANCE_HOVER2_SUB_TITLE_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_hover = Configuration::get(
            'REASSURANCE_HOVER2_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        $h2_back_hover = Configuration::get(
            'REASSURANCE_HOVER2_BACKGROUND_COLOR',
            false,
            $this->context->shop->id_shop_group,
            $this->context->shop->id
        );
        if (_PS_VERSION_ > 1.6) {
            $reassurance = StaticReassuranceModel::getReassuranceDetail($id_lang, $id_shop, $apperance);
            $this->context->smarty->assign(array(
                'reassurance' => $reassurance,
                'base_dir' => _PS_BASE_URL_ . __PS_BASE_URI__,
                'base_dir_ssl' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
                'force_ssl' => $force_ssl,
                'home' => $home,
                'product' => $product,
                'footer' => $footer,
                'c_hover' => $c_hover,
                'c_title' => $c_title,
                'c_subtitle' => $c_sub_title,
                'c_desc' => $c_desc,
                'c_back' => $c_back,
                'c_arrow' => $c_arrow,
                'c_link' => $c_link,
                't_title' => $t_title,
                't_back' => $t_back,
                't_subtitle' => $t_sub_title,
                't_hover' => $t_back_hover,
                't_desc' => $t_desc,
                'h_back_hover' => $h_back_hover,
                'h_hover' => $h_hover,
                'h_title' => $h_title,
                'h_subtitle' => $h_sub_title,
                'h2_back' => $h2_back_hover,
                'h2_hover' => $h2_hover,
                'h2_title' => $h2_title,
                'h2_subtitle' => $h2_sub_title,
            ));
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . $this->name . '/views/templates/hook/reassurance-17.tpl'
            );
        }
    }
    
    public function hookFooter($params)
    {
        $reassurance = $this->reassuranceBlock('footer');
        $hookBlocks = $this->getHookBlocks('footer');
        return $this->getBlock($hookBlocks, $params) . $reassurance;
    }

    public function hookDisplayLeftColumnProduct($params)
    {
        $hookBlocks = $this->getHookBlocks('extraLeft');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayRightColumnProduct($params)
    {
        $hookBlocks = $this->getHookBlocks('extraRight');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookdisplayFooterProduct($params)
    {
        $reassurance = $this->reassuranceBlock('product-footer');
        $hookBlocks = $this->getHookBlocks('displayFooterProduct');
        return $this->getBlock($hookBlocks, $params) . $reassurance;
    }

    public function hookdisplayStaticBlock($params)
    {
        $hookBlocks = $this->getHookBlocks('displayStaticBlock');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayLeftColumn($params)
    {
        $hookBlocks = $this->getHookBlocks('leftColumn');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayRightColumn($params)
    {
        $hookBlocks = $this->getHookBlocks('rightColumn');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayFooterAfter($params)
    {
        $hookBlocks = $this->getHookBlocks('displayFooterAfter');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayFooterBefore($params)
    {
        $hookBlocks = $this->getHookBlocks('displayFooterBefore');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNav1($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNav1');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNav2($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNav2');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNavFullWidth($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNavFullWidth');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $hookBlocks = $this->getHookBlocks('displayProductAdditionalInfo');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayProductExtraContent($params)
    {
        $hookBlocks = $this->getHookBlocks('displayProductExtraContent');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayShoppingCart($params)
    {
        $hookBlocks = $this->getHookBlocks('displayShoppingCart');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        $hookBlocks = $this->getHookBlocks('displayShoppingCartFooter');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayCrossSellingShoppingCart($params)
    {
        $hookBlocks = $this->getHookBlocks('displayCrossSellingShoppingCart');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayCustomerLoginFormAfter($params)
    {
        $hookBlocks = $this->getHookBlocks('displayCustomerLoginFormAfter');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayCustomerAccount($params)
    {
        $hookBlocks = $this->getHookBlocks('displayCustomerAccount');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNotFound($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNotFound');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNotificationError($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNotificationError');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNotificationInfo($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNotificationInfo');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNotificationSuccess($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNotificationSuccess');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayNotificationWarning($params)
    {
        $hookBlocks = $this->getHookBlocks('displayNotificationWarning');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayCheckoutSummaryTop($params)
    {
        $hookBlocks = $this->getHookBlocks('displayCheckoutSummaryTop');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayOrderConfirmation($params)
    {
        $hookBlocks = $this->getHookBlocks('displayOrderConfirmation');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayOrderConfirmation1($params)
    {
        $hookBlocks = $this->getHookBlocks('displayOrderConfirmation1');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayOrderConfirmation2($params)
    {
        $hookBlocks = $this->getHookBlocks('displayOrderConfirmation2');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayPaymentByBinaries($params)
    {
        $hookBlocks = $this->getHookBlocks('displayPaymentByBinaries');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayPaymentReturn($params)
    {
        $hookBlocks = $this->getHookBlocks('displayPaymentReturn');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookPayment($params)
    {
        $hookBlocks = $this->getHookBlocks('payment');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayReassurance($params)
    {
        $hookBlocks = $this->getHookBlocks('displayReassurance');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayMaintenance($params)
    {
        $hookBlocks = $this->getHookBlocks('displayMaintenance');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplayCMSDisputeInformation($params)
    {
        $hookBlocks = $this->getHookBlocks('displayCMSDisputeInformation');
        return $this->getBlock($hookBlocks, $params);
    }

    public function hookDisplaySearch($params)
    {
        $hookBlocks = $this->getHookBlocks('displaySearch');
        return $this->getBlock($hookBlocks, $params);
    }

    public function upgradeModuleToNewVersion()
    {
        $default = StaticReassuranceModel::addDefaultValues();
        $res = StaticblockModel::addNewValuesForUpgrade();
        return $default. $res;
    }
}
