<?php

namespace App\PortalBundle\Services;

class Paypal implements GenericPaymentServiceInterface
{
    public function getHtml($url, $parameters, $displaySubmitBtn, $message)
    {
        return 'PaypalServiceForm';
    }

    /**
     * @inheritdoc
     */
    public function addFail()
    {
        // send mail and log error
    }

    /**
     * @inheritdoc
     */
    public function isTypeMatch($labelClass)
    {
        return $labelClass === $this->getLabel();
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return 'Paypal';
    }
}