<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\EventListener;

use Chamilo\SettingsBundle\Manager\SettingsManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class UserLocaleListener
 *
 * Stores the locale of the user in the session after the
 * login. This can be used by the LocaleListener afterwards.
 *
 * @package Chamilo\CoreBundle\EventListener
 */
class UserLocaleListener
{
    /**
     * @var Session
     */
    private $session;
    /** @var SettingsManager */
    private $settings;

    public function __construct(Session $session, $settings)
    {
        $this->session = $session;
        $this->settings = $settings;
    }

    /**
     * Set locale when user enters the platform
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }
    }

    /**
     * @param GetResponseEvent $event
     */
    public function setLocaleForUnauthenticatedUser(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        // Getting setting from DB
        $chamiloLocale = $this->settings->getSetting(
            'language.platform_language'
        );

        if (empty($chamiloLocale)) {
            $chamiloLocale = $request->getPreferredLanguage();
        }

        if ('undefined' == $request->getLocale()) {
            //$request->setLocale($request->getPreferredLanguage());
            //$request->setLocale($chamiloLocale);
        }

        $request->setLocale($chamiloLocale);
    }
}
