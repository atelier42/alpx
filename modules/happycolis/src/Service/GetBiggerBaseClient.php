<?php

class GetBiggerBaseClient
{
    const GETBIGGER_TOKEN_CACHE_KEY = 'getbigger.token.key';

    /**
     * @var GetBiggerConfiguration
     */
    protected $config;

    /**
     * @var string
     */
    private $token = null;

    public function __construct()
    {
        $this->config = new GetBiggerConfiguration();

        $this->getToken();
    }

    /**
     * Get oAuth2 token from token endpoint
     *
     * @return void
     */
    private function getToken()
    {
        if (\Cache::isStored(self::GETBIGGER_TOKEN_CACHE_KEY)) {
            $this->token = \Cache::retrieve(self::GETBIGGER_TOKEN_CACHE_KEY);
            return;
        }

        $url = $this->config->getHappyColisEndpoint() . "/oauth2/token/".$this->config->getApplicationId()."?"
            . "grant_type=client_credentials"
            . "&client_id=" . urlencode($this->config->getApiIdentifier())
            . "&client_secret=" . urlencode($this->config->getApiSecret())
            . "&scopes[]=" . urlencode($this->config->getApplicationId());

        $json = $this->post($url);

        if ($json && array_key_exists('access_token', $json)) {
            \Cache::store(
                self::GETBIGGER_TOKEN_CACHE_KEY,
                $json['access_token']
            );

            $this->token = $json['access_token'];
        }
    }

    public function post($url, $data = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Data must be already in JSON
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Content-Type:application/json',
                'Accept:application/json',
                'Authorization:' . sprintf('Bearer %s', $this->token)
            ]
        );

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = curl_error($ch);
        $errno = curl_errno($ch);

        if (0 !== $errno || ($httpCode < 200 || $httpCode >= 300)) {
            \PrestaShopLogger::addLog(
                sprintf(
                    'Error while calling "%s" : "%s". Response status code : "%s". Posted data : "%s".',
                    $url,
                    $error,
                    $httpCode,
                    json_encode($data)
                )
            );

            return null;
        }

        if (is_resource($ch)) {
            curl_close($ch);
        }

        return json_decode($response, true);
    }
}
