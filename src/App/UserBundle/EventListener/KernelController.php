<?php

namespace App\UserBundle\EventListener;

use App\PortalBundle\Controller\ContactController;
use App\PortalBundle\Controller\HomeController;
use Symfony\Bundle\AsseticBundle\Controller\AsseticController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\ProfilerController;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class KernelController
{
    /**
     * @var AuthorizationChecker $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

    public function __construct(
        AuthorizationChecker $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerArray = $event->getController();

        if (!is_array($controllerArray)) {
            return;
        }

        if (!$controllerArray[0] instanceof ProfilerController
            && !$controllerArray[0] instanceof RedirectController
            && !$controllerArray[0] instanceof ExceptionController
            && !$controllerArray[0] instanceof AsseticController
            && method_exists($controllerArray[0],'getRequest')) {

            $controller = $controllerArray[0]->getRequest()->attributes->get('_controller');
            preg_match("/\\\\(.*)Bundle\\\\/", $controller, $matches);

            $bundle = trim(current($matches), "\\");

            if ('PortalBundle' == $bundle) {
                if (
                !$controllerArray[0] instanceof HomeController
                    && !$controllerArray[0] instanceof ContactController
                ) {
                    $user  = $this->tokenStorage->getToken()->getUser();

                    if (!is_object($user)) {
                        return;
                    }

                    $roles = $user->getRoles();
                    $role  = $roles[0];

                    if ('ROLE_VISITOR' === $role) {
                        $cgvRead = $user->isCgvRead();
                        if (!$cgvRead) {
                            $redirectRoute = 'sales_conditions';
                            $redirectUrl = $this->router->generate($redirectRoute);
                            $event->setController(function() use ($redirectUrl) {
                                return new RedirectResponse($redirectUrl);
                            });
                        }
                    }
                } else {
                    return;
                }
            }
        }
    }
}