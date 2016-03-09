<?php
namespace App\UserBundle\Security;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Psr\Log\LoggerInterface;

class LogoutSuccessHandler implements LogoutHandlerInterface
{
    private $logger;
    
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
    
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $user = $token->getUser();
        $this->logger->info("User " . $user->getEmail() . " has been logged out");

        $response->headers->setCookie(new Cookie('success_connection', '', time() - 3600));
            
        return $response;
    }
}
