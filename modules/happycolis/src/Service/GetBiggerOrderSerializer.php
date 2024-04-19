<?php

/**
 * Main serializer for orders that serialize order before sending to hook listeners
 *
 * Class OrderSerializer
 */
class GetBiggerOrderSerializer
{
    /**
     * @param \Order $order
     */
    public static function serialize($order)
    {
        if ($order instanceof \Order) {
            $defaultLangId = \Context::getContext()->language->id;

            self::addProducts($order);

            $order->address_delivery = new \Address($order->id_address_delivery);
            $address_delivery_country = new \Country($order->address_delivery->id_country);
            $order->address_delivery->country = $address_delivery_country->iso_code;

            $order->address_invoice = new \Address($order->id_address_invoice);
            $address_invoice = new \Country($order->address_invoice->id_country);
            $order->address_invoice->country = $address_invoice->iso_code;
            $order->status = $order->getCurrentStateFull($defaultLangId);

            $order->currency = new \Currency($order->id_currency);
            $order->carrier = new \Carrier($order->id_carrier);
            $order->messages = self::getMessage($order->id);

            if (!isset($data['customer'])) {
                $order->customer = new \Customer($order->id_customer);
            }

            if (empty($order->product_list)) {
                return null;
            }
        }

        return $order;
    }

    /**
     * @param \Order $order
     */
    private static function addProducts($order)
    {
        $products = $order->getProducts();

        $removedTotalPriceTaxIncl = 0;
        $removedTotalPriceTaxExcl = 0;

        foreach ($products as $key => $product) {
            $allowedWarehouses = GetBiggerProductSerializer::getAllowedWarehouses();
            $productWarehouse = GetBiggerProductSerializer::getWarehousesByProductId($product['product_id'], $product['product_attribute_id']);
            if (!empty($allowedWarehouses) && !in_array($productWarehouse['reference'], $allowedWarehouses)) {
                $removedTotalPriceTaxIncl += floatval($product['total_price_tax_incl']);
                $removedTotalPriceTaxExcl += floatval($product['total_price_tax_excl']);
                unset($products[$key]); // delete the product from the order

            }
        }

        if ($removedTotalPriceTaxIncl > 0 && $removedTotalPriceTaxExcl > 0) {
            $order->total_paid_tax_incl = (string)(floatval($order->total_paid_tax_incl) - $removedTotalPriceTaxIncl);
            $order->total_paid_tax_excl = (string)(floatval($order->total_paid_tax_excl) - $removedTotalPriceTaxExcl);
        }

        $order->product_list = $products ? array_values($products) : [];
    }

    private static function getMessage($idOrder)
    {
        $messages = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT `message`
            FROM `' . _DB_PREFIX_ . 'message`
            WHERE `id_order` = ' . (int)$idOrder . '
            ORDER BY `id_message`
        ');

        if (empty($messages)) {
            return '';
        }

        return array_reduce($messages, function ($acc, $current) {
            $acc .= ' '.$current['message'];

            return $acc;
        }, '');
    }
}
