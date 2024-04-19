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

function upgrade_module_1_6_4()
{
    $res = true;
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'dwfproductextrafields` ADD `position` INT NOT NULL DEFAULT \'0\' AFTER `location`;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'dwfproductextrafields` DEFAULT CHARACTER SET utf8;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'dwfproductextrafields_lang` DEFAULT CHARACTER SET utf8;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_extra_field` DEFAULT CHARACTER SET utf8;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_extra_field_shop` DEFAULT CHARACTER SET utf8;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_extra_field_lang` DEFAULT CHARACTER SET utf8;');
    $res &= Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_extra_field_lang` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;');

    return $res;
}
