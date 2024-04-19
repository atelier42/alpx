<?php

/**
 * Class GetBiggerShippingMethodClient
 */
class GetBiggerShippingMethodClient extends GetBiggerBaseClient
{
    /**
     * Gets available shipping methods from GetBigger
     */
    public function getAll()
    {
        $tpApp = \Configuration::get('GETBIGGER_APP_ID');

        if (empty($tpApp)) {
            return [];
        }

        $carriers = [];
        $response = file_get_contents(
            sprintf(
                '%s/%s/%s',
                $this->config->getHappyColisEndpoint(),
                'happycolis/proxy/shipping-method',
                \Configuration::get('GETBIGGER_APP_ID')
            )
        );

        if (empty($response)) {
            return [];
        }

        $shippingMethods = json_decode($response, true);

        foreach ($shippingMethods as $method) {
            $carriers[] = [
                'id' => $method['id'],
                'name' => $method['name'],
                'title' => $method['title'],
                'description' => $method['description']
            ];
        }

        return $carriers;
    }
}
