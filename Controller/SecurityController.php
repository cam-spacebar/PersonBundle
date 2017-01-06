<?php
/**
 * Created by PhpStorm.
 * User: CameronBurns
 * Date: 24/05/2016
 * Time: 2:07 PM
 */

namespace VisageFour\Bundle\PersonBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// note: no longer used by Anchorcards, so can be modified as needed.
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_form")
     * @Template
     */
    public function loginAction(Request $request)
    {
        /** @var $authenticationUtils \Symfony\Component\Security\Http\Authentication\AuthenticationUtils */
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        return $this->render(
            '@Person/Default/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken
            )
        );
    }

    /**
     * @Route("/admin/login_check", name="login_check")
     */
    public function checkAction()
    {
        // route intercepted by Symfony security
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        //die('this should not be displayed');
        // route intercepted by Symfony security
    }
}