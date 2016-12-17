<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Twencha\Bundle\EventRegistrationBundle\Entity\Person;
use VisageFour\Bundle\PersonBundle\Entity\BasePerson;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VisageFour\Bundle\ToolsBundle\Services\BaseEntityManager;

class BasePersonManager extends BaseEntityManager
{
    public function __construct(EntityManager $em, $class, EventDispatcherInterface $dispatcher, LoggerInterface $logger) {
        parent::__construct($em, $class, $dispatcher, $logger);
        // custom config
        // ...
    }

    public function customCreateNew ($email, $firstName = null, $persist = true) {
        // instantiate
        /** @var BasePerson $person */
        $person = parent::createNew(false);

        // configure
        $person->setEmail($email);
        if (!empty($firstName)) {
            $person->setFirstName($firstName);
        }

        $person->setIsRegistered(false);

        // persist
        if ($persist) {
            $this->em->persist($person);
        }

        return $person;
    }

    public function createNewWithValues ($email = '', $mobileNumber = '') {
        $person = $this->createNew($email);

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

        if (empty($response)) {
            // try with normalized mobile number too
            $response       = $this->repo->findOneBy(array(
                'mobileNumber' => $this->mobileNumberNormalized($mobile)
            ));
        }

        return $response;
    }

    public function mobileNumberNormalized ($mobileNum) {
        return str_replace(' ', '', $mobileNum);
    }

    public function doesPersonExistByMobile (Person $person) {
        //dump($this->getPersonByMobile($person->getMobileNumber()));
        return (!empty($this->getPersonByMobile($person->getMobileNumber())));
    }

    public function doesDuplicateExistByMobile (Person $person) {
        $result = $this->repo->findOneBy(
            array (
                'mobileNumberCanonical' => $person->getMobileNumberCanonical()
            )
        );

        return (empty($result)) ? false : true;
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
     * @return null|BasePerson
     */
    public function findOrCreatePersonByEmail ($email) {
        $response = $this->getOnePerson (array (
            'email'     => $email
        ));

        if ($response == NULL) {
            // create new person
            $response = $this->customCreateNew($email);

            $this->em->persist($response);
        }

        return $response;
    }

    /**
     * @param $email
     * @return BasePerson
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

    public function doesPersonExistByEmail ($email) {
        $person = $this->getPersonByEmail($email);

        return (empty($person)) ? false : true;
    }

    public function findByEmail ($emailAddress) {
        $result = $this->repo->findOneBy (array (
                'emailCanonical'     => $emailAddress
            ));

        return $result;
    }
}