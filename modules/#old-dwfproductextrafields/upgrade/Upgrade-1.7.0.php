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

function upgrade_module_1_7_0($object)
{
    $res = true;

    $res &= Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'dwfproductextrafields` ADD `config` TEXT NOT NULL DEFAULT \'\' AFTER `type`');

    $table_name = _DB_PREFIX_.'product_extra_field';
    $fields = Db::getInstance()->ExecuteS('SELECT pef.`id_dwfproductextrafields`, pef.`fieldname` FROM `'._DB_PREFIX_.'dwfproductextrafields` pef WHERE pef.`type` = "image"');
    foreach ($fields as $field) {
        if (!Db::getInstance()->getRow("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '"._DB_NAME_."' AND TABLE_NAME = '".pSQL($table_name)."_shop' AND COLUMN_NAME = '".pSQL($field['fieldname'])."'")) {
            $res &= Db::getInstance()->Execute('ALTER TABLE `'.pSQL($table_name).'_shop` ADD `'.pSQL($field['fieldname']).'` VARCHAR(255) NULL');
        }
    }

    if (version_compare(_PS_VERSION_, '1.7', '>=')) {
        $res &= $object->installFiles() &&
                $object->registerHook('displayProductExtraContent') &&
                $object->registerHook('actionGetProductPropertiesAfter');
    }

    return $res;
}
