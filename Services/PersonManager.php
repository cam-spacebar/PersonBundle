<?php

namespace VisageFour\Bundle\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use VisageFour\Bundle\PersonBundle\Entity\BasePerson;

class PersonManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $this->em->getRepository('PersonBundle:BasePerson');
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
     * @return null|BasePerson
     */
    public function getOnePerson ($parameters) {
        $response     = $this->repo
            ->findOneBy($parameters);

        return $response;
    }

    /**
     * @param $mobileNo
     * @return null|BasePerson
     */
    public function getOrCreatePersonByMobile ($mobileNo) {
        $response = $this->getPerson (array (
            'mobile'        => $mobileNo
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createPerson($mobileNo);

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param $email
     * @return BasePerson
     */
    public function findOrCreatePersonByEmail ($email) {
        $response = $this->getOnePerson (array (
            'email'     => $email
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createPerson($email);

            $this->em->persist($response);
            $this->em->flush();
        }

        return $response;
    }

    /**
     * @param null $email
     * @return BasePerson
     */
    public function createPerson ($email = NULL) {
        $person = new BasePerson ();
        $person->setEmail($email);

        return $person;
    }

    public function __toString() {
        return $this->getEmail();
    }

    public function isUsernameUnique ($username) {
        $result = $this->repo->getOneBy(array (
            'username'      => $username
        ));

        if (!empty($result)) {
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
        }
    }
}