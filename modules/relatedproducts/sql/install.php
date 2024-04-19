<?php
/**
 * FMM Related Products
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @author    FMM Modules
 * @copyright Copyright 2021 © All right reserved
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @category  FMM Modules
 * @package   relatedproducts
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products`(
        `id_serial`         int(11) unsigned NOT NULL auto_increment,
        `id_current`        int(11) unsigned NOT NULL,
        `id_related`        int(11) unsigned NOT NULL,
        `id_combination`    int(11) unsigned NOT NULL,
        `product`           text,
        PRIMARY KEY (`id_serial`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_rules`(
        `id_related_products_rules` int(11) unsigned NOT NULL auto_increment,
        `active` int(3) unsigned NOT NULL,
        `title` text,
        `date_creat` datetime default CURRENT_TIMESTAMP,
        `date_from` date,
        `date_to` date,
        `tags_value` varchar(64),
        `category_box` varchar(64),
        `related_products_list` varchar(64),
        `id_rules` int unsigned NOT NULL,
        `id_page` int unsigned NOT NULL,
        `id_cms_pages` VARCHAR(55),
        `no_products` int(10) unsigned ,
        PRIMARY KEY (`id_related_products_rules`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_rules_lang`(
        `id_related_products_rules` int(11) unsigned NOT NULL,
        `titles` text,
        `id_lang` int NOT NULL
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_rules_shop`(
        `id_related_products_rules_shop` int(11) unsigned NOT NULL auto_increment,
        `id_related_products_rules` int(10) unsigned NOT NULL,
        `id_shop` int(10) NOT NULL,
        PRIMARY KEY(`id_related_products_rules_shop`,`id_related_products_rules`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_visibility` (
        `id_related_products_visibility` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_related_products_rules` int(10) unsigned NOT NULL,
        `selected_opt` int(10),
        `selected_cat` text,
        `selected_prod` text,
        `title` varchar(255),
        `active` int(3) unsigned NOT NULL,
        PRIMARY KEY (`id_related_products_visibility`,`id_related_products_rules`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_visibility_shop` (
        `id_related_products_visibility` INT UNSIGNED NOT NULL,
        `id_shop` int(3) unsigned NOT NULL,
        PRIMARY KEY (`id_related_products_visibility`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
