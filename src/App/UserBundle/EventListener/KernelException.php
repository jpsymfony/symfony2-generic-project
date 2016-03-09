<?php

namespace App\UserBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelException
{
    /**
     * @var Router $router
     */
    private $router;

    /**
     * @var Session $session
     */
    protected $session;

    /**
     * @var Request $request
     */
    protected $request;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof AccessDeniedHttpException || $exception instanceof AccessDeniedException) {
            $this->session->getFlashBag()->add('error', $exception->getMessage());
            $url = $this->router->generate('homepage');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}