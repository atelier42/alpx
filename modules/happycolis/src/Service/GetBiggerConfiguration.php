<?php

class GetBiggerConfiguration
{
    const GETBIGGER_HAPPY_COLIS_ENDPOINT = 'https://prestashop.happycolis.com';
    const HAPPY_COLIS_HOOK_ENDPOINT = '/hooks/message-incoming';

    private $applicationId;
    private $apiIdentifier;
    private $apiSecret;
    private $happyColisEndpoint;

    public function __construct()
    {
        $this->applicationId = $this->getConfigurationValue('GETBIGGER_APP_ID');
        $this->apiIdentifier = $this->getConfigurationValue('GETBIGGER_IDENTIFIER');
        $this->apiSecret = $this->getConfigurationValue('GETBIGGER_SECRET');
        $this->happyColisEndpoint = $this->getConfigurationValue('GETBIGGER_HAPPY_COLIS_ENDPOINT', self::GETBIGGER_HAPPY_COLIS_ENDPOINT);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return bool|string
     */
    private function getConfigurationValue($key, $default = null)
    {
        if (\Configuration::hasKey($key)) {
            return \Configuration::get($key);
        }

        return $default;
    }

    /**
     * @return bool|string
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return bool|string
     */
    public function getApiIdentifier()
    {
        return $this->apiIdentifier;
    }

    /**
     * @return bool|string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @return string
     */
    public function getHappyColisEndpoint()
    {
        return $this->happyColisEndpoint;
    }

    /**
     * @return string
     */
    public function getHookUrl()
    {
        $url = rtrim(trim($this->happyColisEndpoint), '/');

        return sprintf('%s%s', $url, self::HAPPY_COLIS_HOOK_ENDPOINT);
    }
}
