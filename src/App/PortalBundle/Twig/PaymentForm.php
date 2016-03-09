<?php

namespace App\PortalBundle\Twig;

use App\PortalBundle\Services\GenericPaymentService;
use App\PortalBundle\Services\PaymentContainerService;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PaymentForm extends \Twig_Extension
{

    /**
     * @var string $url
     */
    private $url = null;

    /**
     * @var string $defaultPaymentOrganism
     */
    private $defaultPaymentOrganism = null;

    /**
     * @var PaymentContainerService $PaymentContainerService
     */
    private $paymentContainerService;

    public function __construct(PaymentContainerService $paymentContainerService, $configPayment)
    {
        $this->paymentContainerService = $paymentContainerService;
        $this->defaultPaymentOrganism = $configPayment['default'];
        $this->url = $configPayment[$this->defaultPaymentOrganism]['url'];
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('payment_form', array($this ,'getPaymentForm')),
        );
    }

    public function getName()
    {
        return 'payment_form';
    }

    public function getPaymentForm($values, $displaySubmitBtn, $message)
    {
        $clientId = $values['client_id'];
        $description = $values['description'];
        $orderId = $values['order_id'];
        $amount = intval($values['amount'] * 100);

        $parameters = array(
            'AMOUNT' => $amount,
            'CLIENTIDENT' => $clientId,
            'DESCRIPTION' => $description,
            'ORDERID' => $orderId,
        );

        $defaultPaymentOrganismNamespaceClass = '\App\PortalBundle\Services\\' . $this->defaultPaymentOrganism;
        $defaultPaymentOrganismClass = new $defaultPaymentOrganismNamespaceClass();

        if (!$defaultPaymentOrganismClass instanceof GenericPaymentService) {
            throw new NotFoundResourceException('no service found for ' . $this->defaultPaymentOrganism);
        }

        $paymentSolution = null;

        foreach ($this->paymentContainerService->getPaymentServices() as $paymentService) {
            if ($paymentService instanceof $defaultPaymentOrganismClass) {
                $paymentSolution = $paymentService;
            }
        }

        $html = $paymentSolution->getHtml($this->url, $parameters, $displaySubmitBtn, $message);
        return $html;
    }

}
