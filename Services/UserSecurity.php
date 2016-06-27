<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use Platypuspie\AnchorcardsBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserSecurity

{
    protected $em;
    protected $dispatcher;
    protected $logger;
    protected $securityContext;

    /*
     * List of roles available:
     * ROLE_VIEW_PHOTOS
     * ROLE_DOWNLOAD_ORIGINAL_PHOTOS
     * ROLE_VIEW_BACKEND
     * ROLE_UPLOAD_PHOTOS
     * ROLE_LIST_MY_SHOOTS
     * ROLE_CREATE_SHOOT
     * ROLE_VIEW_BACKEND
     * ROLE_LIST_CODESETS
     * ROLE_CREATE_CODESETS
     * ROLE_CREATE_QR_CODES
     * ROLE_ALLOWED_TO_SWITCH
     */
    /**
     * UserSecurity constructor.
     *
     * @param EntityManager                 $em
     * @param EventDispatcherInterface      $dispatcher
     * @param LoggerInterface               $logger
     * @param SecurityContextInterface      $securityContext
     */
    public function __construct($em, $dispatcher, $logger, $securityContext) {
        $this->em               = $em;
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
        $this->securityContext  = $securityContext;
    }

    public function getPersonLoggedIn () {
        /** @var $thisUser User */
        $thisUser = $this->securityContext->getToken()->getUser();
        //$thisPerson = $this->personManager->getPersonById($thisUser->getId());
        $thisPerson = $thisUser->getRelatedPerson();

        return $thisPerson;
    }


    public function checkRole ($roleName, $onErrorRedirect = false) {
        try {
            $this->enforceUserSecurity($roleName);
        } catch (AccessDeniedException $e) {
            //$onErrorRedirect = 'AnchorcardsBundle:admin:adminMenu.html.twig';
            if ($onErrorRedirect != false) {
                $this->render($onErrorRedirect, array (
                    'role'      => $roleName
                ));
            }

            // todo: create a controller instead of die command
            die ('Error: You do not have access to view this page. Please login.');
        }
    }

    public function enforceUserSecurity($role = 'ROLE_USER')
    {
        // todo: update to $authorizationChecker as this mehtod is deprecated in sf 3.0
        if (!$this->securityContext->isGranted($role)) {
            // in Symfony 2.5
            // throw $this->createAccessDeniedException('message!');
            throw new AccessDeniedException('Need '. $role);
        }
    }
}