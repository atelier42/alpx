<?php
/**
* @author : Sathi
* @copyright : Sathi 2020
* @license : 2020
*/

/**
 * Class AdditionalProductListing
 */
 
class AdditionalProductListing extends ObjectModel
{
    public $id_additionalproductlisting;
    public $filter_type;
    public $filter_data;
    public $id_shop;
    public $hook;
    public $product_count;
    public $is_carousel;
    public $width;
    public $nav;
    public $auto;
    public $pause;
    public $pager;
    public $color;
    public $active;
    public $name;
    public $description;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'st_additionalproductlisting',
        'primary' => 'id_additionalproductlisting',
        'multilang' => true,
        'fields' => array(
            'hook' => array('type' => self::TYPE_STRING, 'required' => false),
            'id_shop' => array('type' => self::TYPE_INT),
            'filter_type' => array('type' => self::TYPE_INT),
            'filter_data' => array('type' => self::TYPE_STRING),
            'product_count' => array('type' => self::TYPE_INT),
            'is_carousel' => array('type' => self::TYPE_INT),
            'width' => array('type' => self::TYPE_INT),
            'nav' => array('type' => self::TYPE_INT),
            'auto' => array('type' => self::TYPE_INT),
            'pause' => array('type' => self::TYPE_INT),
            'pager' => array('type' => self::TYPE_INT),
            'color' => array('type' => self::TYPE_STRING),
            'active' => array('type' => self::TYPE_INT),
            'date_add' => array('type' => self::TYPE_DATE),
            'date_upd' => array('type' => self::TYPE_DATE),

            /* Lang fields */
            'name' => array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 190),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true),
        ),
    );
    
    public function add($autoDate = true, $nullValues = true)
    {
        $this->id_shop = ($this->id_shop) ? $this->id_shop : Context::getContext()->shop->id;
        return parent::add($autoDate, $nullValues);
    }
    
    public static function copy($idAdditionalproductlisting)
    {
        $obj = new self((int)$idAdditionalproductlisting);
        if ($obj->id) {
            $obj->id = '';
            $obj->active = 0;
            $obj->save();
            return $obj->id;
        }
        return false;
    }

    public static function getHookInfo($hook, $idLang, $idShop = null)
    {
        if ($idShop == null) {
            $idShop = Context::getContext()->shop->id;
        }
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'st_additionalproductlisting` a
				INNER JOIN `'._DB_PREFIX_.'st_additionalproductlisting_lang` b
                ON (a.id_additionalproductlisting = b.id_additionalproductlisting)
				WHERE a.active = 1
				AND a.hook = "'.pSql($hook).'"
				AND a.id_shop = '.(int)$idShop.'
				AND b.id_lang = '.(int)$idLang;

        return DB::getInstance()->executeS($sql);
    }

    public static function getProductByCatIds($catIds, $limit, $id_lang)
    {
        if (!$catIds) {
            return array();
        }
        $products = array();
        foreach ($catIds as $id_cat) {
            $category = new Category($id_cat);
            if ($categoryProducts = $category->getProducts(
                $id_lang,
                1,
                $limit
            )) {
                $products = array_merge($products, $categoryProducts);
            }
            $products = array_unique($products, SORT_REGULAR);
            
            if (count($products) >= $limit) {
                break;
            }
        }
        $products = self::getProductId($products);
        return array_slice($products, 0, $limit);
    }

    public static function getProductByManufacturerIds($manufacturerIds, $limit, $id_lang)
    {
        if (!$manufacturerIds) {
            return array();
        }
        $products = array();
        foreach ($manufacturerIds as $id_manufacturer) {
            if ($manufacturerProducts = Manufacturer::getProducts(
                $id_manufacturer,
                $id_lang,
                1,
                $limit
            )) {
                $products = array_merge($products, $manufacturerProducts);
            }
            $products = array_unique($products, SORT_REGULAR);
            
            if (count($products) >= $limit) {
                break;
            }
        }
        $products = self::getProductId($products);
        return array_slice($products, 0, $limit);
    }

    public static function getProductBySupplierIds($supplierIds, $limit, $id_lang)
    {
        if (!$supplierIds) {
            return array();
        }
        $products = array();
        foreach ($supplierIds as $id_supplier) {
            if ($supplierProducts = Supplier::getProducts(
                $id_supplier,
                $id_lang,
                1,
                $limit
            )) {
                $products = array_merge($products, $supplierProducts);
            }
            $products = array_unique($products, SORT_REGULAR);
            
            if (count($products) >= $limit) {
                break;
            }
        }
        $products = self::getProductId($products);
        return array_slice($products, 0, $limit);
    }

    public static function getProductId($products)
    {
        $productIds = array();
        foreach ($products as $product) {
            $productIds[] = $product['id_product'];
        }
        return $productIds;
    }

    public static function getRelatedProductIds($id_product, $limit)
    {
        $products = DB::getInstance()->executeS(
            '
            SELECT `id_product_2` as `id_product` FROM `'._DB_PREFIX_.'accessory`
            WHERE `id_product_1` = '.(int)$id_product
        );
        $products = self::getProductId($products);
        $products = array_unique($products, SORT_REGULAR);
        
        return array_slice($products, 0, $limit);
    }
}
