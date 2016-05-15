<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use VisageFour\Bundle\PersonBundle\Entity\BasePerson;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class BasePersonManager
{
    private $em;
    private $repo;
    private $logger;

    public function __construct(
        EntityManager               $em,
        EventDispatcherInterface    $dispatcher,
        LoggerInterface             $logger,
                                    $repoPath = 'PersonBundle:BasePerson'
    ) {
        $this->em           = $em;
        $this->repo         = $this->em->getRepository($repoPath);
        $this->dispatcher   = $dispatcher;
    }

    public function createNew () {
        return new BasePerson();
    }

    public function getPersonByEmail ($email) {
        $response       = $this->repo->findOneBy(array(
            'email' => $email
        ));

        return $response;
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
        $response = $this->getPerson (array (
            'mobile'        => $mobileNo
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createPerson($mobileNo);

            die('die: in PersonManager:getOrcreatePersonbymobile > need to set username before flush/persist');

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param $email
     * @return null|object
     */
    public function findOrCreatePersonByEmail ($email) {
        $response = $this->getOnePerson (array (
            'email'     => $email
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createPerson($email);

            $username = $this->createUniqueUsername($response);

            $response->setUsername($this->createUniqueUsername($response));
            $response->setPassword('1234567890');

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param null $email
     * @return object
     */
    public function createPerson ($email = NULL) {
        $person = $this->createNew();
        $person->setEmail($email);

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