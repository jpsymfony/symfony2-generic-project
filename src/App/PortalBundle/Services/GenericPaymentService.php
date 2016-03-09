<?php

namespace App\PortalBundle\Services;


interface GenericPaymentService
{
    public function getHtml($url, $parameters, $displaySubmitBtn, $message);

    public function addFail();
}