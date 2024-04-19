<?php
/**
 * FMM Product Tabs
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
 * @package   RelatedProducts
 */

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'related_products`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'related_products_rules`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'related_products_rules_lang`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'related_products_visibility`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'related_products_rules_shop`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
