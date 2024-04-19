<?php

/**
 * Class GetBiggerMessageIncomingClient
 */
class GetBiggerMessageIncomingClient extends GetBiggerBaseClient
{
    static $PRODUCT_HOOK_EVENT = [
        'product/update' => 'product/update',
    ];

    static $SUPPLIER_HOOK_EVENT = [
        'supplier/save' => 'supplier/save',
    ];

    static $ORDER_HOOK_EVENT = [
        'order/save' => 'order/save',
        'order/cancel' => 'order/cancel',
        'order/paid' => 'order/paid',
        'order/status-update' => 'order/status-update',
    ];

    /**
     * @param string $event
     * @param mixed $data
     */
    public function send($event, $data)
    {
        if (in_array($event, self::$PRODUCT_HOOK_EVENT)) {
            $data = GetbiggerProductSerializer::serialize($data);
        }

        if (in_array($event, self::$ORDER_HOOK_EVENT)) {
            $data = GetbiggerOrderSerializer::serialize($data);
        }

        if (in_array($event, self::$SUPPLIER_HOOK_EVENT)) {
            $data = GetBiggerSupplierSerializer::serialize($data);
        }

        if (empty($data)) {
            return;
        }

        $this->post(
            $this->config->getHookUrl(),
            $this->wrapMessage($event, $data)
        );
    }

    /**
     * @param string $event
     * @param mixed $data
     *
     * @return string JSon message wrapped
     */
    private function wrapMessage($event, $data)
    {
        return json_encode([
            'event' => $event,
            'issuedAt' => date('c'),
            'source' => [
                'id' => $this->config->getApplicationId(),
                'type' => 'CMS',
                'name' => \Configuration::get('PS_SHOP_NAME'),
            ],
            'destination' => [
                'id' => '5059252f-a173-4e11-9e1f-aaf5b043c1d4',
                'type' => 'GETBIGGER',
                'name' => 'GETBIGGER.IO',
            ],
            'data' => $data
        ]);
    }
}
