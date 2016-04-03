<?php

namespace App\PortalBundle\Services;

class Be2bill implements GenericPaymentServiceInterface
{
    public function getHtml($url, $parameters, $displaySubmitBtn, $message)
    {
        return 'Be2BillServiceForm';
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
        return 'Be2bill';
    }
}