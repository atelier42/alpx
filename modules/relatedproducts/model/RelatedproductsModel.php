<?php
/**
 * Related Products
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *  @author    FME Modules
 *  @copyright 2021 fmemodules All right reserved
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class RelatedproductsModel extends ObjectModel
{
    public $id_current;

    public $id_related;

    public $product;

    public $id_combination;

    public static $definition = array(
        'table' => 'related_products',
        'primary' => 'id_serial',
        'fields' => array(
            'id_current' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_related' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_combination' => array('type' => self::TYPE_INT),
            'product' => array('type' => self::TYPE_STRING),
        ),
    );

    public static function existRelatedProduct($current, $related, $combination)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('related_products');
        $sql->where('`id_current` = ' . (int) $current);
        $sql->where('`id_related` = ' . (int) $related);
        $sql->where('`id_combination` = ' . (int) $combination);
        return (bool) Db::getInstance()->getRow($sql);
    }

    public function insertRecord($id_current, $id_related, $id_combination, $product_name)
    {
        $res = self::existRelatedProduct($id_current, $id_related, $id_combination);
        if (!$res) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'related_products`(
                `id_current`,
                `id_related`,
                `id_combination`,
                `product`
                )
                VALUES(
                ' . (int) $id_current . ',
                ' . (int) $id_related . ',
                ' . (int) $id_combination . ',
                "' . pSQL($product_name) . '"
                )';
            if (Db::getInstance()->execute($sql)) {
                return Db::getInstance()->Insert_ID();
            }
        }
        return false;
    }

    public static function selectRow($update_id)
    {
        return DB::getInstance()->executeS('SELECT
        id_serial,
        id_related,
        product,
        id_combination
            FROM `' . _DB_PREFIX_ . 'related_products`
            WHERE `id_current` = ' . (int) $update_id);
    }

    public function checkRecord($current)
    {
        return DB::getInstance()->executeS('SELECT
        id_current,
        id_related,
        id_combination(S
            FROM `' . _DB_PREFIX_ . 'related_products`
            WHERE `id_current` = ' . (int) $current);
    }

    public static function deleteRelatedRow($id_current, $id_related, $id_comb = 0)
    {
        if (!$id_current) {
            return false;
        }
        return (bool) Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'related_products`
            WHERE `id_current` = ' . (int) $id_current . '
            AND `id_related` = ' . (int) $id_related . '
            AND id_combination = ' . (int) $id_comb);
    }

    public static function getRelatedProductName(
        $id_product,
        $id_product_attribute = null,
        $id_lang = null
    ) {
        if (!$id_lang) {
            $id_lang = (int) Context::getContext()->language->id;
        }

        // creates the query object
        $query = new DbQuery();
        // selects different names, if it is a combination
        if ($id_product_attribute) {
            $query->select('IFNULL(CONCAT(pl.name, \' : \', GROUP_CONCAT(DISTINCT agl.`name`, \' - \', al.name SEPARATOR \', \')),pl.name) as name');
        } else {
            $query->select('DISTINCT pl.name as name');
        }

        // adds joins & where clauses for combinations
        if ($id_product_attribute) {
            $query->from('product_attribute', 'pa');
            $query->join(Shop::addSqlAssociation('product_attribute', 'pa'));
            $query->innerJoin('product_lang', 'pl', 'pl.id_product = pa.id_product AND pl.id_lang = ' .
                (int) $id_lang . Shop::addSqlRestrictionOnLang('pl'));
            $query->leftJoin(
                'product_attribute_combination',
                'pac',
                'pac.id_product_attribute = pa.id_product_attribute'
            );
            $query->leftJoin(
                'attribute',
                'atr',
                'atr.id_attribute = pac.id_attribute'
            );
            $query->leftJoin(
                'attribute_lang',
                'al',
                'al.id_attribute = atr.id_attribute AND al.id_lang = ' . (int) $id_lang
            );
            $query->leftJoin(
                'attribute_group_lang',
                'agl',
                'agl.id_attribute_group = atr.id_attribute_group AND agl.id_lang = ' . (int) $id_lang
            );
            $query->where('pa.id_product = ' .
                (int) $id_product . ' AND pa.id_product_attribute = ' .
                (int) $id_product_attribute);
        } else {
            // or just adds a 'where' clause for a simple product
            $query->from('product_lang', 'pl');
            $query->where('pl.id_product = ' . (int) $id_product);
            $query->where('pl.id_lang = ' .
                (int) $id_lang . Shop::addSqlRestrictionOnLang('pl'));
        }
        return Db::getInstance()->getValue($query);
    }

    public static function getCartRelatedProducts($id_cart)
    {
        if (!$id_cart) {
            return false;
        }
        $sql = new DbQuery();
        $sql->select('cp.id_product');
        $sql->from('cart_product', 'cp');
        $sql->innerJoin('related_products', 'rp', 'rp.`id_current` = cp.`id_product`');
        $sql->where('cp.id_cart = ' . (int) $id_cart);
        $sql->groupBy('cp.`id_product`');

        $result = Db::getInstance()->executeS($sql);
        $products = array();
        if (isset($result) && $result) {
            foreach ($result as $row) {
                $products[] = (int) $row['id_product'];
            }
        }
        return $products;
    }

    public static function getCartsProducts($id_cart)
    {
        if (!$id_cart) {
            return false;
        }
        $sql = new DbQuery();
        $sql->select('cp.id_product');
        $sql->from('cart_product', 'cp');
        $sql->where('cp.id_cart = ' . (int) $id_cart);
        $sql->groupBy('cp.`id_product`');
        $result = Db::getInstance()->executeS($sql);
        $products = array();
        if (isset($result) && $result) {
            foreach ($result as $row) {
                $products[] = (int) $row['id_product'];
            }
        }
        return $products;
    }

    public static function addNewValuesForUpgrade()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_visibility` (
            `id_related_products_visibility` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_related_products_rules` int(10) unsigned NOT NULL,
            `selected_opt` int(10),
            `selected_cat` text,
            `selected_prod` text,
            `title` varchar(255),
            `active` int(3) unsigned NOT NULL,
            PRIMARY KEY (`id_related_products_visibility`,`id_related_products_rules`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;');
        $return &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_visibility_shop` (
            `id_related_products_visibility` INT UNSIGNED NOT NULL,
            `id_shop` int(3) unsigned NOT NULL,
            PRIMARY KEY (`id_related_products_visibility`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;');
        $return &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'related_products_rules_lang`(
            `id_related_products_rules` int(11) unsigned NOT NULL,
            `titles` text,
            `id_lang` int NOT NULL,
            PRIMARY KEY (`id_related_products_rules`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;');
        return $return;
    }

    public static function getCategoryProducts($id_cat)
    {
        if (!$id_cat) {
            return false;
        }

        $sql = new DbQuery();
        $sql->select('p.id_product');
        $sql->from('product', 'p');
        $sql->where('p.id_category_default =' . $id_cat);
        $result = Db::getInstance()->executeS($sql);
        $products = array();
        if (isset($result) && $result) {
            foreach ($result as $row) {
                $products[] = (int) $row['id_product'];
            }
        }
        return $products;
    }

    public static function upgradeV220()
    {
        $sql1 = Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'related_products_rules ADD id_page INT NOT NULL;');
        $sql2 = Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'related_products_rules ADD id_cms_pages VARCHAR(55);');
        
        if ($sql1 && $sql2) {
            return true;
        }
    }
}
