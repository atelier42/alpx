<?php

class WebserviceSpecificManagementGborders implements WebserviceSpecificManagementInterface
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

            $order = new \Order($id);

            if (null === $order->id) {
                return $this->buildClientErrorResponse(404);
            }

            return json_encode(self::formatOrderResponse($order), JSON_UNESCAPED_UNICODE);

        } elseif (null !== Tools::getValue('limit')) {
            $limit = explode(',', Tools::getValue('limit'));

            if (count($limit) !== 2) {
                return $this->buildClientErrorResponse(400);
            }

            $orders = Db::getInstance()->executeS('SELECT `id_order` FROM `'._DB_PREFIX_.'orders` LIMIT '.$limit[0].','.$limit[1].'');

            $result = [];

            foreach ($orders as $idOrder) {
                $result[] = self::formatOrderResponse(new \Order($idOrder['id_order']));
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

    private static function formatOrderResponse($order)
    {
        if (!class_exists('GetbiggerOrderSerializer')) {
            require_once _PS_MODULE_DIR_. 'happycolis/src/Service/GetBiggerOrderSerializer.php';
        }

        return GetbiggerOrderSerializer::serialize($order);
    }
}
