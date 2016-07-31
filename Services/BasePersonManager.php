<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use Platypuspie\AnchorcardsBundle\Entity\person;
use VisageFour\Bundle\PersonBundle\Entity\BasePerson;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class BasePersonManager
{
    // todo:
    // #1: need to use BaseEntityManager
    // #2: update with variable to link to new class (as done in FOSUserBundle:UserManager, instead of overriding class

    protected $em;
    protected $dispatcher;
    protected $logger;
    protected $repo;

    public function __construct(
        EntityManager               $em,
        EventDispatcherInterface    $dispatcher,
        LoggerInterface             $logger,
                                    $repoPath = 'PersonBundle:BasePerson'
    ) {
        $this->em           = $em;
        $this->repo         = $this->em->getRepository($repoPath);
        $this->dispatcher   = $dispatcher;
        $this->logger       = $logger;
    }

    public function createNew () {
        return new BasePerson();
    }

    public function createNewWithValues ($email = '', $mobileNumber = '') {
        $person = $this->createNew();

        $person->setMobileNumber($mobileNumber);
        $person->setEmail($email);

        return $person;
    }

    /**
     * @param $email
     * @return null|BasePerson
     */
    public function getPersonByEmail ($email) {
        //  $this->em->getRepository('PersonBundle:BasePerson')
        $response = $this->repo->findOneBy(array(
            'email' => $email
        ));

        return $response;
    }

    public function getPersonById ($id) {
        $person       = $this->repo->findOneBy(array(
            'id' => $id
        ));
        return $person;
    }

    /**
     * @param $mobile
     * @return null|person
     */
    public function getPersonByMobile ($mobile) {
        $response       = $this->repo->findOneBy(array(
            'mobileNumber' => $mobile
        ));

        return $response;
    }

    public function getPersonByEmailAddressOrMobile ($email, $mobileNo = null) {
        if (isset($email)) {
            $person = $this->getPersonByEmail ($email);
        }

        if (isset($mobileNo) && empty ($person)) {
            return $this->getPersonByMobile($mobileNo);
        }

        return $person;
    }

    public function getOneBy ($parameters) {
        $response       = $this->repo->findOneBy($parameters);

        return $response;
    }

    /**
     * @param $parameters
     * @return null|object
     */
    public function getOnePerson ($parameters) {
        $response     = $this->repo
            ->findOneBy($parameters);

        return $response;
    }

    /**
     * @param $mobileNo
     * @return null|object
     */
    public function getOrCreatePersonByMobile ($mobileNo) {
        $response = $this->getOneBy(array (
            'mobileNumber'        => $mobileNo
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createNew();
            $response->setMobileNumber($mobileNo);

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param $email
     * @return null|person
     */
    public function findOrCreatePersonByEmail ($email) {
        $response = $this->getOnePerson (array (
            'email'     => $email
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createNew();
            $response->setEmail($email);

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param $email
     * @return person
     */
    public function findOrCreatePersonByMobile ($mobileNumber) {
        $person = $this->getOnePerson (array (
            'mobileNumber'     => $mobileNumber
        ));

        if ($person == NULL) {
            // create person
            $person = $this->createNew();
            $person->setMobileNumber($mobileNumber);

            $this->em->persist($person);
            $this->em->flush();
        }

        //dump($person);

        return $person;
    }

    public function __toString() {
        return $this->getEmail();
    }

    public function isUsernameUnique ($username) {
        /*
         $result = $this->repo->findOneBy(array (
            'username'      => $username
        ));
        // */

        $query="SELECT * FROM BasePerson WHERE 1";
        $rsm = new ResultSetMapping();
        $stmt = $this->em->getConnection()->prepare($query);
        //$stmt = $this->em->createNativeQuery($query, $rsm);
        //dump($stmt->getResult());

        //$stmt = $conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();
        dump($results);
        //dump($stmt->execute());

        // todo: need to add unique search in base class for username
        // last developed 14/05/2016
        // see this article for help:
        // http://jayroman.com/blog/symfony2-quirks-with-doctrine-inheritance-and-unique-constraints#comment-2673678187
        die('died at isUsernameUnique');

        if (empty($result)) {
            return true;
        }

        return false;
    }

    public function createUniqueUsername (BasePerson $basePerson) {
        if (!empty($basePerson->getUsername())) {
            throw new \Exception ('user already has a username');
        }

        $username = $basePerson->getEmail();
        if ($this->isUsernameUnique($username)) {
            return $username;
        }

        for ($i=1; 1; $i++) {
            $uniqueUsername = $username.$i;

            if ($this->isUsernameUnique($uniqueUsername)) {
                return $uniqueUsername;
            }

            if ($i>500) {
                throw new \Exception ('been through 500 loops to try to find a username, die();');
            }
        }
    }
}