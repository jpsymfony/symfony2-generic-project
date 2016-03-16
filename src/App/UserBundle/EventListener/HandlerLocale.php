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

    protected $switchLanguageAuthorized;

    protected $allLocales;

    /**
     * Constructor.
     *
     * @param string $defaultLocale Locale value
     * @param $switchLanguageAuthorized
     * @param $allLocales
     */
    public function __construct($defaultLocale = 'fr', $switchLanguageAuthorized, $allLocales)
    {
        $this->defaultLocale = $defaultLocale;
        $this->switchLanguageAuthorized = $switchLanguageAuthorized;
        $this->allLocales = $allLocales;
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
        $isSwitchLanguageBrowserAuthorized = $this->switchLanguageAuthorized;
        $allLocales = $this->allLocales;
        // Sets the user local value.
        if ($isSwitchLanguageBrowserAuthorized && !$islocale) {
            $langValue = $this->request->getPreferredLanguage();
            if (in_array($langValue, $allLocales)) {
                $this->request->setLocale($langValue);
                return;
            }
        }
        if ($islocale && !empty($localevalue)) {
            $this->request->attributes->set('_locale', $localevalue); // for _locale routing parameter
            $this->request->setLocale($localevalue);
        } else {
            $this->request->attributes->set('_locale', $this->defaultLocale); // for _locale routing parameter
            $this->request->setLocale($this->defaultLocale);
        }
    }
}
