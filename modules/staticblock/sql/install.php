<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    FME Modules
 *  @copyright © 2018 FME Modules
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block`(
    `id_static_block`           int(10) unsigned NOT NULL auto_increment,
    `id_static_block_template`  int(10) unsigned NOT NULL DEFAULT 0,
    `hook`                      varchar(256),
    `editor`                    int(10) unsigned NOT NULL DEFAULT 1,
    `status`                    tinyint(1) unsigned NOT NULL DEFAULT 1,
    `custom_css`                tinyint(1) unsigned NOT NULL DEFAULT 0,
    `title_active`              tinyint(1) unsigned NOT NULL DEFAULT 1,
    `position`                  tinyint(5) unsigned NOT NULL DEFAULT 0,
    `css`                       LONGTEXT,
    `date_from`                 datetime,
    `date_to`                   datetime,
    `date_add`                  datetime,
    `date_upd`                  datetime,
    PRIMARY KEY                 (`id_static_block`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_lang`(
    `id_static_block`           int(10) unsigned NOT NULL,
    `id_lang`                   int(10) unsigned NOT NULL,
    `block_title`               VARCHAR(1000) NOT NULL,
    `content`                   LONGTEXT,
    PRIMARY KEY                 (`id_static_block`,`id_lang`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_shop`(
    `id_static_block`           int(10) unsigned NOT NULL,
    `id_shop`                   int(10) unsigned NOT NULL,
    PRIMARY KEY                 (`id_static_block`,`id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_group`(
    `id_static_block`           int(10) unsigned NOT NULL,
    `id_group`                  int(10) unsigned NOT NULL,
    PRIMARY KEY                 (`id_static_block`,`id_group`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_template`(
    `id_static_block_template`  int(10) unsigned NOT NULL auto_increment,
    `template_name`             VARCHAR(1000) NOT NULL,
    `status`                    tinyint(1) unsigned NOT NULL DEFAULT 1,
    `code`                      LONGTEXT,
    PRIMARY KEY                 (`id_static_block_template`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_html_rule_condition`(
    `id_static_block_html_rule_condition`       int(10) unsigned NOT NULL auto_increment,
    `id_static_block_html_rule_condition_group` int(10) unsigned NOT NULL,
    `type`                                      varchar(128) NOT NULL,
    `operator`                                  varchar(128) NOT NULL,
    `value`                                     VARCHAR(1000) NOT NULL,
    PRIMARY KEY                                 (`id_static_block_html_rule_condition`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'static_block_html_rule_condition_group`(
    `id_static_block_html_rule_condition_group` int(10) unsigned NOT NULL auto_increment,
    `id_static_block`                           int(10) unsigned NOT NULL,
    PRIMARY KEY                                 (`id_static_block_html_rule_condition_group`,`id_static_block`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance`(
    `id_fmm_reassurance`       int(10) unsigned NOT NULL auto_increment,
    `status`                    tinyint(1) unsigned NOT NULL DEFAULT 0,
    `image`                     text,
    `link`                      text,
    `apperance`                 varchar(100) NOT NULL,
    PRIMARY KEY                                 (`id_fmm_reassurance`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_lang`(
    `id_fmm_reassurance`           int(10) unsigned NOT NULL,
    `id_lang`                   int(10) unsigned NOT NULL,
    `title`                     VARCHAR(1000) NOT NULL,
    `sub_title`                 VARCHAR(1000) NOT NULL,
    `description`               LONGTEXT,
    `id_shop`                   int(10) unsigned NOT NULL,
    PRIMARY KEY                 (`id_fmm_reassurance`,`id_lang`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_shop`(
    `id_fmm_reassurance`           int(10) unsigned NOT NULL,
    `id_shop`                   int(10) unsigned NOT NULL,
    PRIMARY KEY                 (`id_fmm_reassurance`,`id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . 'DEFAULT CHARSET=utf8';

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
