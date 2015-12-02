<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\EventListener;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Routing\Router;

/**
 * Class LogoutSuccessHandler
 */
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    protected $router;
    protected $checker;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param AuthorizationChecker $checker
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, AuthorizationChecker $checker)
    {
        $this->router = $urlGenerator;
        $this->checker = $checker;
    }

    /**
     * @param Request $request
     * @return null|RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        // Chamilo logout

        $request->getSession()->remove('_locale');
        $request->getSession()->remove('_locale_user');

        $login = $this->router->generate('home');
        $response = new RedirectResponse($login);

        return $response;
    }
}
