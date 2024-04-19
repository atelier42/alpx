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
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_shop`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_group`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_template`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_html_rule_condition`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_html_rule_condition_group`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fmm_reassurance_shop`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'static_block_hooks`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
