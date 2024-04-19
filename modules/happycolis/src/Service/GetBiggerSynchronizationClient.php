<?php

/**
 * Class GetBiggerSynchronizationClient
 */
class GetBiggerSynchronizationClient extends GetBiggerBaseClient
{
    /**
     * @var GetBiggerMessageIncomingClient
     */
    private $client;

    public function __construct()
    {
        $this->client = new GetBiggerMessageIncomingClient();
    }

    /**
     * Synchronization of all products
     *
     * @params boolean $active
     */
    public function syncProducts($active)
    {
        $num = 0;

        $ids = $this->searchProducts($active);

        foreach ($ids as $id) {
            $this->client->send(
                GetBiggerMessageIncomingClient::$PRODUCT_HOOK_EVENT['product/update'],
                new \Product($id)
            );

            $num++;
        }

        return $num;
    }

    /**
     * Synchronization of all orders
     *
     * @param array $idsOrderState
     */
    public function syncOrders($idsOrderState, $dateFromFilter, $dateToFilter)
    {
        $num = 0;

        $ids = $this->searchOrders($idsOrderState, $dateFromFilter, $dateToFilter);

        foreach ($ids as $id) {
            $this->client->send(
                GetBiggerMessageIncomingClient::$ORDER_HOOK_EVENT['order/save'],
                new \Order($id)
            );

            $num++;
        }

        return $num;
    }

    /**
     * Synchronization of all suppliers
     */
    public function syncSuppliers()
    {
        $num = 0;

        $ids = $this->searchSupplier();

        foreach ($ids as $id) {
            $this->client->send(
                GetBiggerMessageIncomingClient::$SUPPLIER_HOOK_EVENT['supplier/save'],
                new \Supplier($id)
            );

            $num++;
        }

        return $num;
    }

    /**
     * @param int[] $idsOrderState
     * @param string $dateFromFilter
     * @param string $dateToFilter
     *
     * @return int[]
     */
    private function searchOrders($idsOrderState, $dateFromFilter, $dateToFilter)
    {
        $where = ' WHERE 1 = 1 ';

        if ($idsOrderState && is_array($idsOrderState) && count($idsOrderState) > 0) {
            $where .= ' AND o.`current_state` IN (' . implode(', ', $idsOrderState) . ') ';
        }

        if ($dateFromFilter) {
            $where .= ' AND o.`date_add` >=  \''.$dateFromFilter.' 00:00:00\' ';
        }

        if ($dateToFilter) {
            $where .= ' AND o.`date_add` <=  \''.$dateToFilter.' 23:59:59\' ';
        }

        $sql = 'SELECT id_order FROM ' . _DB_PREFIX_ . 'orders o'
                . $where .
                Shop::addSqlRestriction(false, 'o') . '
                ORDER BY date_add DESC';
        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return array_map(function ($order) {
            return (int)$order['id_order'];
        }, $result);
    }

    /**
     * @param boolean $active
     *
     * @return int[]
     */
    private function searchProducts($active)
    {
        $where = ' WHERE 1 = 1 ';

        if ($active) {
            $where .= ' AND p.active = 1 ';
        }

        $products = Db::getInstance()->executeS(
            sprintf('SELECT `id_product` FROM `%sproduct` p %s ', _DB_PREFIX_, $where)
        );

        return array_map(function ($product) {
            return (int)$product['id_product'];
        }, $products);
    }

    /**
     * @return int[]
     */
    private function searchSupplier()
    {
        $suppliers = Db::getInstance()->executeS(
            sprintf('SELECT `id_supplier` FROM `%ssupplier`', _DB_PREFIX_)
        );

        return array_map(function ($supplier) {
            return (int)$supplier['id_supplier'];
        }, $suppliers);
    }
}
