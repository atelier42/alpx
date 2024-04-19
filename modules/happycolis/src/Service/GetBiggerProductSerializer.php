<?php

/**
 * Main serializer for products that serialize product before sending to hook listeners
 *
 * Class ProductSerializer
 */
class GetBiggerProductSerializer
{
    /**
     * @param \Product $product
     */
    public static function serialize($product)
    {
        if (! ($product instanceof \Product)) {
            return $product;
        }

        $manager = \StockManagerFactory::getManager();

        $product = new \Product($product->id, true);

        $defaultLangId = \Context::getContext()->language->id;

        $product->attribute_combinations = self::getAttributesResume($product->id, $defaultLangId);

        // product with combination
        if (empty($product->attribute_combinations) && !empty($product->getAttributeCombinations())) {
            return null;
        }

        $product->warehouse = self::getWarehousesByProductId($product->id);

        // simple product, without combination
        if (empty($product->getAttributeCombinations($defaultLangId))) {
            $allowedWarehouses = self::getAllowedWarehouses();

            if (!empty($allowedWarehouses) && isset($product->warehouse['reference']) && !in_array($product->warehouse['reference'], $allowedWarehouses)) {
                return null;
            }
        }

        $product->real_quantity = \Product::getRealQuantity($product->id);
        $product->physical_quantities = $manager->getProductPhysicalQuantities($product->id, null);
        $product->price_tax_included = $product->getPrice(true);

        self::handleNameField($product);
        self::handleDescriptionField($product);
        self::handleDescriptionShortField($product);

        return $product;
    }

    public static function serializeFromCombination($idProductAttribute)
    {
        $idProduct = \Db::getInstance()->getValue(
            sprintf('SELECT id_product FROM %sproduct_attribute where id_product_attribute = %d', _DB_PREFIX_, $idProductAttribute)
        );

        $data = ['product' => new \Product($idProduct)];

        return self::serialize($data);
    }

    private static function handleNameField($product)
    {
        $defaultLangId = \Context::getContext()->language->id;

        if (is_array($product->name)) {
            foreach ($product->name as $langId => $name) {
                if (!\Language::getLanguage($langId)) {
                    continue;
                }

                unset($product->name[$langId]);
                $product->name[\Language::getIsoById($langId)] = $name;
            }
        } else {
            $productName = $product->name;
            unset($product->name);
            $product->name[\Language::getIsoById($defaultLangId)] = $productName;
        }
    }

    private static function handleDescriptionField($product)
    {
        $defaultLangId = \Context::getContext()->language->id;

        if (is_array($product->description)) {
            foreach ($product->description as $langId => $description) {
                if (!\Language::getLanguage($langId)) {
                    continue;
                }

                unset($product->description[$langId]);
                $product->description[\Language::getIsoById($langId)] = $description;
            }
        } else {
            $productDescription = $product->description;
            unset($product->description);
            $product->description[\Language::getIsoById($defaultLangId)] = $productDescription;
        }
    }

    private static function handleDescriptionShortField($product)
    {
        $defaultLangId = \Context::getContext()->language->id;

        if (is_array($product->description_short)) {
            foreach ($product->description_short as $langId => $shortDescription) {
                if (!\Language::getLanguage($langId)) {
                    continue;
                }

                $locale = str_replace('-', '_', \Language::getIsoById($langId));

                unset($product->description_short[$langId]);
                $product->description_short[$locale] = $shortDescription;
            }
        } else {
            $productDescriptionShort = $product->description_short;
            unset($product->description_short);
            $product->description_short[\Language::getIsoById($defaultLangId)] = $productDescriptionShort;
        }
    }

    public static function getAttributesResume($idProduct, $id_lang)
    {
        $allowedWarehouses = self::getAllowedWarehouses();

        $attribute_value_separator = ' ';
        $attribute_separator = ', ';

        if (!\Combination::isFeatureActive()) {
            return array();
        }

        $combinations = \Db::getInstance()->executeS('SELECT pa.*, product_attribute_shop.*
                FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                ' . \Shop::addSqlAssociation('product_attribute', 'pa') . '
                WHERE pa.`id_product` = ' . (int) $idProduct . '
                GROUP BY pa.`id_product_attribute`', true, false);

        if (!$combinations) {
            return [];
        }

        $product_attributes = array();
        foreach ($combinations as $combination) {
            $product_attributes[] = (int) $combination['id_product_attribute'];
        }

        $lang = \Db::getInstance()->executeS('SELECT pac.id_product_attribute, GROUP_CONCAT(agl.`name`, \'' . pSQL($attribute_value_separator) . '\',al.`name` ORDER BY agl.`id_attribute_group` SEPARATOR \'' . pSQL($attribute_separator) . '\') as attribute_designation
                FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) $id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int) $id_lang . ')
                WHERE pac.id_product_attribute IN (' . implode(',', $product_attributes) . ')
                GROUP BY pac.id_product_attribute', true, false);

        foreach ($lang as $k => $row) {
            $combinations[$k]['attribute_designation'] = $row['attribute_designation'];
        }

        //Get quantity of each variations
        foreach ($combinations as $key => $row) {
            $combinationWarehouse = self::getWarehousesByProductId($row['id_product'], $row['id_product_attribute']);

            $result = \StockAvailable::getQuantityAvailableByProduct($row['id_product'], $row['id_product_attribute']);
            $combinations[$key]['quantity'] = $result;
            $combinations[$key]['warehouse'] = $combinationWarehouse;

            if(!empty($allowedWarehouses) && !in_array($combinationWarehouse['reference'], $allowedWarehouses)) {
                unset($combinations[$key]);
            }
        }

        return $combinations ? $combinations : [];
    }

    public static function getWarehousesByProductId($id_product, $id_product_attribute = 0)
    {
        if (!$id_product && !$id_product_attribute) {
            return array();
        }

        $query = new DbQuery();
        $query->select('w.*');
        $query->from('warehouse', 'w');
        $query->leftJoin('warehouse_product_location', 'wpl', 'wpl.id_warehouse = w.id_warehouse');
        if ($id_product) {
            $query->where('wpl.id_product = ' . (int) $id_product);
        }
        if ($id_product_attribute) {
            $query->where('wpl.id_product_attribute = ' . (int) $id_product_attribute);
        }
        $query->orderBy('w.reference ASC');
        $query->groupBy('w.id_warehouse');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    }

    public static function getAllowedWarehouses()
    {
        $allowedWarehouses = \Configuration::get('GETBIGGER_WAREHOUSE');
        if ($allowedWarehouses) {
            $allowedWarehouses = explode(',', $allowedWarehouses);
        }

        return $allowedWarehouses;
    }
}
