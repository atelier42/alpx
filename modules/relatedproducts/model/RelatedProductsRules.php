<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2021 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class RelatedProductsRules extends ObjectModel
{
    public $promo_position;
    public $active;
    public $date_from;
    public $date_to;
    public $titles;
    public $tags_value;
    public $category_box;
    public $related_products_list;
    public $id_rules;
    public $id_page;
    public $id_cms_pages;
    public $no_products;
    public static $definition = array(
        'table' => 'related_products_rules',
        'primary' => 'id_related_products_rules',
        'multilang' => true,
        'fields' => array(
            'titles' => array(
                'type' => self::TYPE_STRING,
                'lang' => true, 'validate' => 'isString',
            ),
            'date_from' => array('type' => self::TYPE_DATE),
            'date_to' => array('type' => self::TYPE_DATE),
            'tags_value' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ),
            'category_box' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ),
            'related_products_list' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ),
            'id_cms_pages' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'id_rules' => array('type' => self::TYPE_INT),
            'id_page' => array('type' => self::TYPE_INT),
            'no_products' => array('type' => self::TYPE_INT),
        ),
    );

    public static function getAllRules()
    {
        $context = Context::getContext();
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('related_products_rules', 'rpl');
        $sql->leftJoin(
            'related_products_rules_lang',
            'rpll',
            'rpl.id_related_products_rules=rpll.id_related_products_rules'
        );
        $sql->leftJoin(
            'related_products_visibility',
            'rpmr',
            'rpl.id_related_products_rules=rpmr.id_related_products_rules'
        );
        $sql->innerJoin(
            'related_products_rules_shop',
            'rprp',
            'rprp.id_related_products_rules = rpl.id_related_products_rules'
        );
        $sql->where(
            'rpl.date_from <=" ' . pSQL(date('Y-m-d')) . '" or date_from="0000-00-00"'
        );
        $sql->where(
            'rpl.date_to >= "' . pSQL(date('Y-m-d')) . '" or date_to="0000-00-00"'
        );
        $sql->where('rpl.active=1');
        $sql->where('rprp.id_shop=' . $context->shop->id);
        $sql->where('rpll.id_lang=' . $context->language->id);

        return Db::getInstance()->executeS($sql);
    }

    public static function getSameCategroyProducts($id_product, $no_products)
    {
        $sql = new DbQuery();
        $sql->select('id_category_default');
        $sql->from('product', 'p');
        $sql->where('id_product=' . (int) $id_product);
        $default_category_pro = Db::getInstance()->getValue($sql);
        $sql = new DbQuery();
        $sql->select('cp.id_product');
        $sql->from('product', 'p');
        $sql->innerJoin('category_product', 'cp', 'p.id_product=cp.id_product');
        $sql->where('p.active=1');
        $sql->where('p.available_for_order=1');
        $sql->where('cp.id_category=' . (int) $default_category_pro);
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $samecategory_attr = array();
        foreach ($result as $key => $value) {
            $samecategory_attr[$key]['id_related'] = $value['id_product'];
            $samecategory_attr[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['id_product']
            );
        }
        return $samecategory_attr;
    }

    public static function getSameBrandProducts($id_product, $no_products)
    {
        $sql1 = new DbQuery();
        $sql1->select('pr.id_manufacturer');
        $sql1->from('product', 'pr');
        $sql1->where('pr.id_product=' . (int) $id_product);
        $sql = new DbQuery();
        $sql->select('p.id_product');
        $sql->from('product', 'p');
        $sql->where('p.available_for_order=1');
        $sql->where('p.active=1');
        $sql->limit((int) $no_products);
        $sql->where('p.id_manufacturer=(' . $sql1 . ')');
        $result = Db::getInstance()->executeS($sql);
        $samebrand_attr = array();
        foreach ($result as $key => $value) {
            $samebrand_attr[$key]['id_related'] = $value['id_product'];
            $samebrand_attr[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['id_product']
            );
        }
        return $samebrand_attr;
    }

    public static function getSameBrandcategoryProducts($id_product, $no_products)
    {
        $sql1 = new DbQuery();
        $sql1->select('pr.id_manufacturer');
        $sql1->from('product', 'pr');
        $sql1->where('pr.id_product=' . (int) $id_product);
        $sql2 = new DbQuery();
        $sql2->select('pd.id_category_default');
        $sql2->from('product', 'pd');
        $sql2->where('pd.id_product=' . (int) $id_product);
        $sql = new DbQuery();
        $sql->select('p.id_product');
        $sql->from('product', 'p');
        $sql->where('p.available_for_order=1');
        $sql->where('p.active=1');
        $sql->where('p.id_manufacturer=(' . $sql1 . ')');
        $sql->where('p.id_category_default=(' . $sql2 . ')');
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $samebrandcat_attr = array();
        foreach ($result as $key => $value) {
            $samebrandcat_attr[$key]['id_related'] = $value['id_product'];
            $samebrandcat_attr[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['id_product']
            );
        }
        return $samebrandcat_attr;
    }

    public static function getTopViewedProducts($no_products)
    {
        $sql = new DbQuery();
        $sql->select('p.id_object,pt.name,pv.counter');
        $sql->from('page_viewed', 'pv');
        $sql->leftJoin('page', 'p', 'p.id_page=pv.id_page');
        $sql->innerJoin('product', 'pro', 'pro.id_product=p.id_object');
        $sql->innerJoin('page_type', 'pt', ' pt.id_page_type=p.id_page_type');
        $sql->orderBy('pv.counter desc');
        $sql->where('pt.name = "product"');
        $sql->where('pro.available_for_order=1');
        $sql->where('pro.active=1');
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $ids = array_column($result, 'id_object');
        $ids = array_unique($ids);
        $result = array_filter($result, function ($key, $value) use ($ids) {
            $key = $key;
            return in_array($value, array_keys($ids));
        }, ARRAY_FILTER_USE_BOTH); //for removing replicate data
        $topviewed_attr = array();
        foreach ($result as $key1 => $value) {
            $topviewed_attr[$key1]['id_related'] = $value['id_object'];
            $topviewed_attr[$key1]['id_combination'] = Product::getDefaultAttribute(
                $value['id_object']
            );
        }
        return $topviewed_attr;
    }

    public static function getrelatedCustomerBoughtPro($id_product, $no_products)
    {
        $sql1 = new DbQuery();
        $sql1->select('id_order');
        $sql1->from('order_detail', 'od');
        $sql1->where('od.product_id =' . (int) $id_product);
        $sql1->innerJoin('product', 'pro', 'pro.id_product=od.product_id');
        $sql1->where('pro.available_for_order=1');
        $sql1->where('pro.active=1');
        $sql = new DbQuery();
        $sql->select('ord.product_id');
        $sql->from('order_detail', 'ord');
        $sql->where('ord.id_order in(' . $sql1 . ')');
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $custboughtbor_attr = array();
        foreach ($result as $key => $value) {
            $custboughtbor_attr[$key]['id_related'] = $value['product_id'];
            $custboughtbor_attr[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['product_id']
            );
        }
        return $custboughtbor_attr;
    }

    public static function getrelatedwithTags($no_products, $tags)
    {
        $tags = explode(',', $tags);
        $tags_value = "'" . implode("', '", $tags) . "'";
        $sql = new DbQuery();
        $sql->select('pt.id_product');
        $sql->from('product_tag', 'pt');
        $sql->innerJoin('tag', 't', 'pt.id_tag=t.id_tag');
        $sql->innerJoin('product', 'pro', 'pro.id_product=pt.id_product');
        $sql->where('pro.available_for_order=1');
        $sql->where('pro.active=1');
        $sql->where('t.name in' . '(' . $tags_value . ')');
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $getrelatedwithTags = array();
        foreach ($result as $key => $value) {
            $getrelatedwithTags[$key]['id_related'] = $value['id_product'];
            $getrelatedwithTags[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['id_product']
            );
        }
        return $getrelatedwithTags;
    }

    public static function getrelatedwithCat($no_products, $cat)
    {
        $cat = explode(',', $cat);
        $cat_value = "'" . implode("', '", $cat) . "'";
        $sql = new DbQuery();
        $sql->select('pt.id_product');
        $sql->from('product_tag', 'pt');
        $sql->innerJoin('tag', 't', 'pt.id_tag=t.id_tag');
        $sql->innerJoin('product', 'pro', 'pro.id_product=pt.id_product');
        $sql->where('pro.available_for_order=1');
        $sql->where('pro.active=1');
        $sql->where('t.name in' . '(' . $cat_value . ')');
        $sql->limit((int) $no_products);
        $result = Db::getInstance()->executeS($sql);
        $getrelatedwithCat = array();
        foreach ($result as $key => $value) {
            $getrelatedwithCat[$key]['id_related'] = $value['id_product'];
            $getrelatedwithCat[$key]['id_combination'] = Product::getDefaultAttribute(
                $value['id_product']
            );
        }
        return $getrelatedwithCat;
    }

    public static function insertLangData($id_rule, $id_lang, $title)
    {
        return Db::getInstance()->insert(
            'related_products_rules_lang',
            array(
                'id_related_products_rules' => (int) $id_rule,
                'id_lang' => (int) $id_lang,
                'titles' => pSQL($title),
            )
        );
    }

    public static function getSearchProducts()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die(json_encode('Found Nothing.'));
        }

        /*
         * In the SQL request the "q" param is used entirely to match result in database.
         * In this way if string:"(ref : #ref_pattern#)" is displayed on the return list,
         * they are no return values just because string:"(ref : #ref_pattern#)"
         * is not write in the name field of the product.
         * So the ref pattern will be cut for the search request.
         */
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }

        // Excluding downloadable products from packs because download from pack is not supported
        $forceJson = Tools::getValue('forceJson', false);
        $disableCombination = Tools::getValue('disableCombination', false);
        $excludeVirtuals = (bool) Tools::getValue('excludeVirtuals', true);
        $exclude_packs = (bool) Tools::getValue('exclude_packs', true);

        $context = Context::getContext();
        $sql = 'SELECT
        p.`id_product`,
        pl.`link_rewrite`,
        p.`reference`,
        pl.`name`,
        image_shop.`id_image` id_image,
        il.`legend`,
        p.`cache_default_attribute`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                    ON (pl.id_product = p.id_product AND pl.id_lang = ' .
        (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' .
        (int) $context->shop->id . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' .
        (int) $context->language->id . ')
                WHERE (pl.name LIKE \'%' . pSQL($query) .
        '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\')' .
            (!empty($excludeIds) ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
            ($excludeVirtuals ?
            'AND NOT EXISTS (
                SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : ''
        ) .
            ($exclude_packs ?
            'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
            ' GROUP BY p.id_product';

        $items = Db::getInstance()->executeS($sql);
        if ($items && ($disableCombination || $excludeIds)) {
            $results = array();
            foreach ($items as $item) {
                if (!$forceJson) {
                    $item['name'] = str_replace('|', '&#124;', $item['name']);
                    $results[] = trim($item['name']) .
                    (!empty($item['reference']) ? ' (ref: ' .
                        $item['reference'] . ')' : '') . '|' . (int) $item['id_product'];
                } else {
                    $cover = Product::getCover($item['id_product']);
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => $item['name'] .
                        (!empty($item['reference']) ? ' (ref: ' .
                            $item['reference'] . ')' : ''),
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace(
                            'http://',
                            Tools::getShopProtocol(),
                            $context->link->getImageLink(
                                $item['link_rewrite'],
                                (($item['id_image']) ? $item['id_image'] : $cover['id_image']),
                                self::getFormatedName('home')
                            )
                        ),
                    );
                }
            }

            if (!$forceJson) {
                echo implode("\n", $results);
            } else {
                echo json_encode($results);
            }
        } elseif ($items) {
            // packs
            $results = array();
            foreach ($items as $item) {
                // check if product have combination
                if (Combination::isFeatureActive() && $item['cache_default_attribute']) {
                    $sql = 'SELECT
                    pa.`id_product_attribute`,
                    pa.`reference`,
                    ag.`id_attribute_group`,
                    pai.`id_image`,
                    agl.`name` AS group_name,
                    al.`name` AS attribute_name,
                                a.`id_attribute`
                            FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                            ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                            ON pac.`id_product_attribute` = pa.`id_product_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a
                            ON a.`id_attribute` = pac.`id_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag
                            ON ag.`id_attribute_group` = a.`id_attribute_group`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al
                            ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' .
                    (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl
                            ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' .
                    (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON
                            pai.`id_product_attribute` = pa.`id_product_attribute`
                            WHERE pa.`id_product` = ' . (int) $item['id_product'] . '
                            GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                            ORDER BY pa.`id_product_attribute`';

                    $combinations = Db::getInstance()->executeS($sql);
                    if (!empty($combinations)) {
                        foreach ($combinations as $combination) {
                            $cover = Product::getCover($item['id_product']);
                            $results[$combination['id_product_attribute']]['id'] = $item['id_product'];
                            $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination[
                                'id_product_attribute'
                            ];
                            !empty($results[$combination['id_product_attribute']]['name']) ?
                            $results[$combination['id_product_attribute']]['name'] .= ' ' .
                            $combination['group_name'] . '-' . $combination['attribute_name']
                            : $results[$combination['id_product_attribute']]['name'] = $item['name'] .
                                ' ' . $combination['group_name'] . '-' . $combination['attribute_name'];
                            if (!empty($combination['reference'])) {
                                $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                            } else {
                                $results[$combination['id_product_attribute']]['ref'] = !empty(
                                    $item['reference']
                                ) ? $item['reference'] : '';
                            }
                            if (empty($results[$combination['id_product_attribute']]['image'])) {
                                $results[$combination['id_product_attribute']]['image'] = str_replace(
                                    'http://',
                                    Tools::getShopProtocol(),
                                    $context->link->getImageLink(
                                        $item['link_rewrite'],
                                        (($combination['id_image']) ? $combination['id_image'] : $cover['id_image']),
                                        self::getFormatedName('home')
                                    )
                                );
                            }
                        }
                    } else {
                        $results[] = array(
                            'id' => $item['id_product'],
                            'name' => $item['name'],
                            'ref' => (!empty($item['reference']) ?
                                $item['reference'] : ''),
                            'image' => str_replace(
                                'http://',
                                Tools::getShopProtocol(),
                                $context->link->getImageLink(
                                    $item['link_rewrite'],
                                    $item['id_image'],
                                    self::getFormatedName('home')
                                )
                            ),
                        );
                    }
                } else {
                    $results[] = array(
                        'id' => $item['id_product'],
                        'name' => $item['name'],
                        'ref' => (!empty($item['reference']) ?
                            $item['reference'] : ''),
                        'image' => str_replace(
                            'http://',
                            Tools::getShopProtocol(),
                            $context->link->getImageLink(
                                $item['link_rewrite'],
                                $item['id_image'],
                                self::getFormatedName('home')
                            )
                        ),
                    );
                }
            }
            echo json_encode(array_values($results));
        } else {
            echo json_encode(array());
        }
    }

    public static function getFormatedName($name)
    {
        $theme_name = Context::getContext()->shop->theme_name;
        $name_without_theme_name = str_replace(array('_' . $theme_name, $theme_name . '_'), '', $name);
        //check if the theme name is already in $name if yes only return $name
        if (strstr($name, $theme_name) && ImageType::getByNameNType($name, 'products')) {
            return $name;
        } elseif (ImageType::getByNameNType($name_without_theme_name . '_' . $theme_name, 'products')) {
            return $name_without_theme_name . '_' . $theme_name;
        } elseif (ImageType::getByNameNType($theme_name . '_' . $name_without_theme_name, 'products')) {
            return $theme_name . '_' . $name_without_theme_name;
        } else {
            return $name_without_theme_name . '_default';
        }
    }
}
