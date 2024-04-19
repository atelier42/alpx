<?php
/**
 * Upsells
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *  @author    FME Modules
 *  @copyright 2020 fmemodules All right reserved
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  @category  FMM Modules
 *  @package   Product Contact
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_2_0($module)
{
    $module = $module;
    $sql = array();
    $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_hooks`(
    `id_static_block_hook`           int(10) unsigned NOT NULL auto_increment,
    `hook_name`                   VARCHAR(1000) NOT NULL,
    `hook_title`                   VARCHAR(1000) NOT NULL,
    PRIMARY KEY                 (`id_static_block_hook`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

    foreach ($sql as $query) {
        if (Db::getInstance()->execute($query) == false) {
            return false;
        }
    }
    return true;
}
