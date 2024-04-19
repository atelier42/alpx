<?php

class WebserviceSpecificManagementGbproducts implements WebserviceSpecificManagementInterface
{
    /** @var WebserviceOutputBuilder */
    protected $objOutput;

    /** @var WebserviceRequest */
    protected $wsObject;
    protected $output;

    /**
     * {@inheritDoc}
     */
    public function setObjectOutput(WebserviceOutputBuilderCore $obj)
    {
        $this->objOutput = $obj;

        return $this;
    }


    /**
     * {@inheritDoc}
     */
    public function getObjectOutput()
    {
        return $this->objOutput;
    }

    /**
     * {@inheritDoc}
     */
    public function setWsObject(WebserviceRequestCore $obj)
    {
        $this->wsObject = $obj;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getWsObject()
    {
        return $this->wsObject;
    }

    /**
     * {@inheritDoc}
     */
    public function manage()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getContent()
    {
        // if id exist in the path
        if (isset($this->wsObject->urlSegment[1])) {
            $id = $this->wsObject->urlSegment[1];

            $product = new \Product($id);

            if (null === $product->id) {
                return $this->buildClientErrorResponse(404);
            }

            return json_encode(self::formatProductResponse($product), JSON_UNESCAPED_UNICODE);
        } elseif (null !== Tools::getValue('limit')) {
            $limit = explode(',', Tools::getValue('limit'));

            if (count($limit) !== 2) {
                return $this->buildClientErrorResponse(400);
            }

            $products = Db::getInstance()->executeS(
                sprintf('SELECT `id_product` FROM `%sproduct` LIMIT %s,%s', _DB_PREFIX_, $limit[0], $limit[1])
            );

            $result = [];

            foreach ($products as $idProduct) {
                $result[] = self::formatProductResponse(new \Product($idProduct['id_product']));
            }

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([], JSON_UNESCAPED_UNICODE);
    }

    private function buildClientErrorResponse($status)
    {
        $this->objOutput->setStatus($status);
        $this->wsObject->setOutputEnabled(false);

        return null;
    }

    private static function formatProductResponse($product)
    {
        if (!class_exists('GetbiggerProductSerializer')) {
            require_once _PS_MODULE_DIR_. 'happycolis/src/Service/GetBiggerProductSerializer.php';
        }

        return GetbiggerProductSerializer::serialize($product);
    }

}
