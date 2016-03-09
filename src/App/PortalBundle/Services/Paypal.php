<?php

namespace App\PortalBundle\Services;

class Paypal implements GenericPaymentService
{
    public function getHtml($url, $parameters, $displaySubmitBtn, $message)
    {
        return 'PaypalServiceForm';
    }

    public function addFail()
    {
        // send mail and log error
    }
}