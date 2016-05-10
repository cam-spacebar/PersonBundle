<?php

namespace VisageFour\PersonBundle\Services;

use Doctrine\ORM\EntityManager;
use VisageFour\PersonBundle\Entity\person;

class PersonManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $this->em->getRepository('PersonBundle:person');
    }

    public function getPersonByEmailAddress ($EmailAddress) {
        $response       = $this->repo->findOneBy(array(
            'emailAddress' => $EmailAddress
        ));

        return $response;
    }

    /**
     * @param $parameters
     * @return null|person
     */
    public function getOnePerson ($parameters) {
        $response     = $this->repo
            ->findOneBy($parameters);

        return $response;
    }

    /**
     * @param $mobileNo
     * @return null|person
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
     * @return person
     */
    public function findOrCreatePersonByEmail ($email) {
        $response = $this->getOnePerson (array (
            'emailAddress'     => $email
        ));

        if ($response == NULL) {
            // create person
            $response = $this->createPerson($email);

            $em->persist($response);
            $em->flush();
        }

        return $response;
    }

    /**
     * @param null $emailAddress
     * @return person
     */
    public function createPerson ($emailAddress = NULL) {
        $person = new person ();
        $person->setEmailAddress($emailAddress);

        return $person;
    }

    public function __toString() {
        return $this->getEmailAddress();
    }
}