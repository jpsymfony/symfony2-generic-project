<?php

namespace App\PortalBundle\Twig;

use App\CoreBundle\Services\Utils;
use App\PortalBundle\Services\GenericPaymentService;
use App\PortalBundle\Services\PaymentContainerService;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
        $this->defaultPaymentOrganism = ucfirst($configPayment['default']);
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

        $defaultPaymentOrganismClass =  $this->getPaymentClass($this->defaultPaymentOrganism);

        if (!$defaultPaymentOrganismClass instanceof GenericPaymentService) {
            throw new NotFoundResourceException('no GenericPaymentService found for ' . $this->defaultPaymentOrganism);
        }

        $paymentSolution = null;

        foreach ($this->paymentContainerService->getPaymentServices() as $paymentService) {
            if ($paymentService instanceof $defaultPaymentOrganismClass) {
                $paymentSolution = $paymentService;
            }
        }

        return $paymentSolution->getHtml($this->url, $parameters, $displaySubmitBtn, $message);
    }

    private function getPaymentClass($class)
    {
        $isValidClass = $this->isValidPaymentClass($class);
        if ($isValidClass['valid']) {
            return new $isValidClass['class']();
        } else {
            throw new NotFoundResourceException('no Service found for ' . $this->defaultPaymentOrganism);
        }
    }

    private function isValidPaymentClass($class)
    {
        $utils = new Utils();
        $bundles = $utils->getBundlesList();

        $isValidClass = false;
        $nameSpaceClass = '';

        foreach ($bundles as $bundle) {
            $nameSpaceClass = '\App\\'. $bundle .'\Services\\' . ucfirst($class);
            if (!class_exists($nameSpaceClass)) {
                continue;
            } else {
                $isValidClass = true;
                break;
            }
        }

        if (!$isValidClass) {
            return [
                'valid' => false,
                'class' => '',
            ];
        }

        return [
            'valid' => true,
            'class' => $nameSpaceClass,
        ];
    }

}
