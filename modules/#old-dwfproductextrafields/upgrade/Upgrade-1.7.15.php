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

function upgrade_module_1_7_15($object)
{
    return Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'dwfproductextrafields_lang` ADD `hint` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `label`');
}
