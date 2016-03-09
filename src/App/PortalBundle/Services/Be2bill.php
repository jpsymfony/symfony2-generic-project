<?php

namespace App\PortalBundle\Services;

class Be2bill implements GenericPaymentService
{
    public function getHtml($url, $parameters, $displaySubmitBtn, $message)
    {
        return 'Be2BillServiceForm';
    }

    public function addFail()
    {
        // send mail and log error
    }
}