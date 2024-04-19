<?php

/**
 * Class GetBiggerCarrierCreator
 */
class GetBiggerCarrierCreator
{
    /**
     * @var GetBiggerShippingMethodClient
     */
    private $shippingMethodClient;

    public function __construct()
    {
        $this->shippingMethodClient = new GetBiggerShippingMethodClient();
    }

    public function create()
    {
        $resultMessages = '';
        $carrierIDs = [];

        $carriers = $this->shippingMethodClient->getAll();

        $defaultLangId = \Context::getContext()->language->id;
        $inError = false;

        if (empty($carriers)) {
            $inError = true;
            $resultMessages .= $this->displayError('An error occurred while getting carriers from HappyColis API.');
        }

        foreach ($carriers as $carrier) {
            $newCarrier = new \Carrier(null, $defaultLangId);
            $newCarrier->delay = sprintf('GETBIGGER - %s', $carrier['description']);
            $newCarrier->name = sprintf('GETBIGGER - %s', $carrier['title']);
            $newCarrier->active = false;

            try {
                if (!$newCarrier->add()) {
                    $inError = true;
                    $resultMessages .= $this->displayError(sprintf('An error occurred while saving this carrier "%s" .', $carrier['title']));
                } else {
                    $carrierIDs[] = $newCarrier->id;
                }
            } catch (\PrestaShopException $e) {
                $inError = true;
                $resultMessages .= $this->displayError(sprintf('An error occurred while saving this carrier "%s" (%s).', $carrier['title'], $e->getMessage()));
            }
        }

        if (!$inError) {
            \Configuration::updateValue('GETBIGGER_CREATE_CARRIERS', true);
            \Configuration::updateValue('GETBIGGER_CREATED_CARRIERS_IDS', json_encode($carrierIDs));

            $resultMessages .= $this->displayConfirmation('Les transporteurs ont été crée avec succès.');
        }

        return $resultMessages;
    }

    public function displayConfirmation($string)
    {
        return '
        <div class="bootstrap">
        <div class="module_confirmation conf confirm alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ' . $string . '
        </div>
        </div>';
    }

    /**
     * Helper displaying error message(s).
     *
     * @param string|array $error
     *
     * @return string
     */
    public function displayError($error)
    {
        $output = '
        <div class="bootstrap">
        <div class="module_error alert alert-danger" >
            <button type="button" class="close" data-dismiss="alert">&times;</button>';

        if (is_array($error)) {
            $output .= '<ul>';
            foreach ($error as $msg) {
                $output .= '<li>' . $msg . '</li>';
            }
            $output .= '</ul>';
        } else {
            $output .= $error;
        }

        // Close div openned previously
        $output .= '</div></div>';

        return $output;
    }
}
