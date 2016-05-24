<?php
/**
 * Created by PhpStorm.
 * User: CameronBurns
 * Date: 24/05/2016
 * Time: 2:07 PM
 */

namespace VisageFour\Bundle\PersonBundle\Controller;

use Platypuspie\AnchorcardsBundle\Controller\PublicSiteController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_form")
     * @Template
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $activeRoute    = 'login_form';
        $navigation     = PublicSiteController::getNavigation($activeRoute);

        return $this->render(
            '@Person/Default/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'navigation'    => $navigation,
            )
        );
    }

    /**
     * @Route("/admin/login_check", name="login_check")
     */
    public function checkAction()
    {
    }
}