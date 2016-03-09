<?php

namespace App\PortalBundle\Services;

class PaymentContainerService
{
    private $paiementServices;

    public function __construct()
    {
        $this->paiementServices = array();
    }

    public function addPaymentService(GenericPaymentService $paiementService)
    {
        $this->paiementServices[] = $paiementService;
    }

    public function getPaymentServices()
    {
        return $this->paiementServices;
    }
}