<?php
namespace App\UserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Custom locale handler.
 *
 * @category   EventListener
 * @package    Handler
 * @subpackage Request
 */
class HandlerLocale
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var Request $request The service request
     */
    protected $request;

    protected $switch_language_authorized;

    protected $all_locales;

    /**
     * Constructor.
     *
     * @param string $defaultLocale Locale value
     * @param $switch_language_authorized
     * @param $all_locales
     */
    public function __construct($defaultLocale = 'fr', $switch_language_authorized, $all_locales)
    {
        $this->defaultLocale = $defaultLocale;
        $this->switch_language_authorized = $switch_language_authorized;
        $this->all_locales = $all_locales;
    }

    /**
     * Invoked to modify the controller that should be executed.
     *
     * @param GetResponseEvent $event The event
     *
     * @access public
     * @return null|void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // ne rien faire si ce n'est pas la requête principale
            return;
        }
        $this->request = $event->getRequest($event);

        $islocale = $this->request->cookies->has('_locale');
        $localevalue = $this->request->cookies->get('_locale');
        $isSwitchLanguageBrowserAuthorized = $this->switch_language_authorized;
        $all_locales = $this->all_locales;
        // Sets the user local value.
        if ($isSwitchLanguageBrowserAuthorized && !$islocale) {
            $lang_value = $this->request->getPreferredLanguage();
            if (in_array($lang_value, $all_locales)) {
                $this->request->setLocale($lang_value);
                return;
            }
        }

        if ($islocale && !empty($localevalue)) {
            $this->request->setLocale($localevalue);
        } else {
            $this->request->setLocale($this->defaultLocale);
        }
    }
}
