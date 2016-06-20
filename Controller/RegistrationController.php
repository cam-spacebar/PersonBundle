<?php

namespace VisageFour\Bundle\PersonBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Platypuspie\AnchorcardsBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisageFour\Bundle\PersonBundle\Form\UserRegistrationFormType;

class RegistrationController extends Controller
{
    /**
     * @Route("/registrationComplete", name="security_registrationComplete")
     */
    public function RegistrationCompleteAction (Request $request) {
        /** @var $navigationService \Platypuspie\AnchorcardsBundle\Services\Navigation */
        $navigationService  = $this->container->get('anchorcardsbundle.navigation');
        $navigation         = $navigationService->getNavigation('security_registrationComplete');

        return $this->render('@Person/Default/registrationComplete.html.twig', array(
            'navigation'    => $navigation
        ));
    }

    /**
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/register", name="security_registerUser")
     */
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        /** @var $navigationService \Platypuspie\AnchorcardsBundle\Services\Navigation */
        $navigationService  = $this->container->get('anchorcardsbundle.navigation');
        $navigation         = $navigationService->getNavigation('security_registerUser');
        /** @var $userManager \Platypuspie\AnchorcardsBundle\Services\UserManager */
        $userManager        = $this->container->get('anchorcards.user_manager');


        /** @var $personManager \Platypuspie\AnchorcardsBundle\Services\PersonManager */
        $personManager      = $this->container->get('platypuspie.personmanager');
        /*
         * // set email address

        $person = $personManager->getPersonByEmail('cameronrobertburns@gmail.com');
        $person->setEmail('cameronrobertburns@gmail.com');
        $personManager->updatePerson ($person);
        die ('updated person');
        // */


        // ADD ROLE_ADMIN
        
        $user = $userManager->findUserByEmail('cameronrobertburns@gmail.com');

        $role = 'ROLE_ADMIN';
        $user->addRole($role);
        $userManager->updateUser($user);
        PRINT 'user updated as '. $role;
        dump($user->getRoles()); die();
        // /*


        /** @var $mailer \FOS\UserBundle\Mailer\Mailer */
        /* SEND CONFIRMATION EMAIL
        //dump($user); die();
        $mailer = $this->get('fos_user.mailer');
        $mailer->sendConfirmationEmailMessage($user);
        // */


        /*
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        // */

        $user = new User();
        $form = $this->createForm('VisageFour\Bundle\PersonBundle\Form\UserRegistrationFormType', $user);
        $form->handleRequest($request);

        if (
            ($this->container->get('kernel')->getEnvironment() == 'dev') &&
            (!($form->isSubmitted()))
        ) {
            UserRegistrationFormType::setDefaultData ($form);
        }

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);

            $flashBag = $this->get('session')->getFlashBag();

            // check no other user exists with email address
            if ($userManager->doesUserEmailExist ($user)) {
                $flashBag->set('error', 'A user with the email address: "'. $user->getEmail() .'" already exists.');
            } else {
                $user->setUsername($user->getEmail());
                $userManager->findRelatedPerson($user, $personManager);

                //dump($user); die();

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $userManager->updateUser($user);

                $flashBag->set('success', 'Success. Your account: "'. $user->getEmail() .'" has been created.');

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('security_registrationComplete');
                    $response = new RedirectResponse($url);
                }

                //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->render('@Person/Default/registration.html.twig', array(
            'form'          => $form->createView(),
            'navigation'    => $navigation
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        /** @var $navigationService \Platypuspie\AnchorcardsBundle\Services\Navigation */
        $navigationService  = $this->container->get('anchorcardsbundle.navigation');
        $navigation         = $navigationService->getNavigation('security_registrationComplete');

        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('@Person/Default/checkEmail.html.twig', array(
            'user'          => $user,
            'navigation'    => $navigation
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        /** @var $navigationService \Platypuspie\AnchorcardsBundle\Services\Navigation */
        $navigationService  = $this->container->get('anchorcardsbundle.navigation');
        $navigation         = $navigationService->getNavigation('security_registrationComplete');

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('PersonBundle:Default:confirmed.html.twig', array(
            'user'          => $user,
            'targetUrl'     => $this->getTargetUrlFromSession(),
            'navigation'    => $navigation
        ));
    }

    private function getTargetUrlFromSession()
    {
        // Set the SecurityContext for Symfony <2.6
        if (interface_exists('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')) {
            $tokenStorage = $this->get('security.token_storage');
        } else {
            $tokenStorage = $this->get('security.context');
        }

        $key = sprintf('_security.%s.target_path', $tokenStorage->getToken()->getProviderKey());

        if ($this->get('session')->has($key)) {
            return $this->get('session')->get($key);
        }
    }
}
