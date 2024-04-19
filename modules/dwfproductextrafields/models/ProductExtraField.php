<?php
/**
 *   2009-2021 ohmyweb!
 *
 *   @author    ohmyweb <contact@ohmyweb.fr>
 *   @copyright 2009-2021 ohmyweb!
 *   @license   Proprietary - no redistribution without authorization
 */

use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

require_once _PS_MODULE_DIR_.'dwfproductextrafields/models/DwfProductExtraFieldsClass.php';

$moduleManagerBuilder = ModuleManagerBuilder::getInstance();
$moduleManager = $moduleManagerBuilder->build();

if ($moduleManager->isInstalled('dwfproductextrafields')) {
    $fields = DwfProductExtraFieldsClass::getActiveFields();

    $content  = "class ProductExtraField extends ObjectModel { \n";
    $content .= "public \$id_product; \n";
    $content .= "public \$id_shop_default; \n";
    foreach ($fields as $field) {
        $content .= 'public $'.$field->fieldname.'; '."\n";
    }

    $content .= "public \$date_add; \n";
    $content .= "public \$date_upd; \n";
    $content .= " \n";
    $content .= "public function __construct(\$id = null, \$id_lang = null, \$id_shop = null) { \n";
    $content .= "    Shop::addTableAssociation('product_extra_field', array('type' => 'shop')); \n";
    $content .= "    Shop::addTableAssociation('product_extra_field_lang', array('type' => 'fk_shop')); \n";
    $content .= "    parent::__construct(\$id, \$id_lang, \$id_shop); \n";
    $content .= "} \n";
    $content .= "\n";
    $content .= "public static \$definition = array( \n";
    $content .= "    'table' => 'product_extra_field', \n";
    $content .= "    'primary' => 'id_product_extra_field', \n";
    $content .= "    'multilang' => true, \n";
    $content .= "    'multilang_shop' => true, \n";
    $content .= "    'fields' => array( \n";
    $content .= "        'id_shop_default' =>             array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'), \n";
    $content .= "        'id_product' =>                 array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'), \n";
    foreach ($fields as $field) {
        switch ($field->type) {
            case 'color':
            case 'image':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isGenericName', 'size' => 255), \n";
                break;

            case 'integer':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isInt'), \n";
                break;

            case 'decimal':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isFloat'), \n";
                break;

            case 'price':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice'), \n";
                break;

            case 'date':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_DATE, 'shop' => true), \n";
                break;

            case 'datetime':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_DATE, 'shop' => true), \n";
                break;

            case 'checkbox':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_BOOL, 'shop' => true), \n";
                break;

            case 'selector':
            case 'custom':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_STRING, 'shop' => true, 'lang' => false, 'validate' => 'isString'), \n";
                break;

            case 'non-translatable_text':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_HTML, 'shop' => true, 'lang' => false, 'validate' => 'isString'), \n";
                break;

            case 'text':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255), \n";
                break;

            case 'textarea':
            case 'repeater':
            case 'textarea_mce':
                $content .= "        '".$field->fieldname."' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'), \n";
                break;
        }
    }
    $content .= "        'date_add' =>                     array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'), \n";
    $content .= "        'date_upd' =>                     array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'), \n";

    $content .= "    ), \n";
    $content .= "); \n";
    $content .= " \n";
    $content .= "protected \$webserviceParameters = [ \n";
    $content .= "    'objectNodeName' => 'product_extra_field', \n";
    $content .= "    'objectsNodeName' => 'product_extra_fields', \n";
    $content .= "]; \n";
    $content .= " \n";
    $content .= "static public function getByIdProduct(\$id_product) { \n";
    $content .= "  if(\$id_product_extra_field = Db::getInstance()->getValue('SELECT pef.id_product_extra_field \n";
    $content .= "                          FROM '._DB_PREFIX_.'product_extra_field pef WHERE pef.id_product = '.pSQL(\$id_product))) { \n";
    $content .= "      \$extraField = new ProductExtraField(\$id_product_extra_field); \n";
    $content .= "      return \$extraField; \n";
    $content .= "  } \n";
    $content .= "  else { \n";
    $content .= "      \$extraField = new ProductExtraField(); \n";
    $content .= "      \$extraField->id_product = \$id_product; \n";
    $content .= "      return \$extraField; \n";
    $content .= "  } \n";
    $content .= "} \n";
    $content .= " \n";
    $content .= "static public function getProductExtraFieldValue(\$product_id, \$fieldname = array()) { \n";
    $content .= "  \$context = Context::getContext(); \n";
    $content .= "  if (empty(\$product_id) ||empty(\$fieldname)) { \n";
    $content .= "      return; \n";
    $content .= "  } \n";
    $content .= "  if (strpos(\$fieldname, 'dwf_') === 0) { \n";
    $content .= "    \$fieldname = substr(\$fieldname, 4); \n";
    $content .= "  } \n";
    $content .= "  if(\$id_product_extra_field = Db::getInstance()->getValue('SELECT pef.id_product_extra_field \n";
    $content .= "                          FROM '._DB_PREFIX_.'product_extra_field pef WHERE pef.id_product = '.pSQL(\$product_id))) { \n";
    $content .= "      \$extraField = new ProductExtraField(\$id_product_extra_field, \$context->language->id); \n";
    $content .= "      if (!empty(\$extraField->{\$fieldname})) { \n";
    $content .= "          return \$extraField->{\$fieldname}; \n";
    $content .= "      } \n";
    $content .= "  } \n";
    $content .= "  return; \n";
    $content .= "} \n";
    $content .= " \n";
    $content .= "} \n";

    eval($content);
}
