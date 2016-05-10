<?php

namespace VisageFour\Personbundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twencha\TwenchaBundle\Entity\registration;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="Twencha\TwenchaBundle\Repository\personRepository")
 */
class person extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=5, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=20, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=20, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="emailAddress", type="string", length=75, unique=true)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="mobileNumber", type="string", length=75, unique=true)
     */
    private $mobileNumber;

    /**
     */
    private $relatedRegistrationList;

    // $registrationStatus constants: to determine the state of the event registration
    const NO_EMAIL_PROVIDED             = 0;        // user has not yet provided an email address (usually used when a person is instantiated)
    const EMAIL_PROVIDED                = 1;        // user has provided email only when registering (not enough to send to ESP
    const NAME_AND_EMAIL_PROVIDED       = 2;        // user has provided email and name + title - enough to send through to ESP

    /**
     * @var int
     *
     * @ORM\Column(name="registrationStatus", type="integer")
     */
    private $registrationStatus;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return person
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param \Twencha\TwenchaBundle\Entity\registration $relatedRegistrationList
     * @return $this
     */
    public function addRelatedRegistration(registration $relatedRegistrationList)
    {
        $this->relatedRegistrationList->add($relatedRegistrationList);

        return $this;
    }

    /**
     * Get relatedRegistrationList
     *
     * @return int
     */
    public function getRelatedRegistrationList()
    {
        return $this->relatedRegistrationList;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getRegistrationStatus()
    {
        return $this->registrationStatus;
    }

    /**
     * @param int $registrationStatus
     */
    public function setRegistrationStatus($registrationStatus)
    {
        $this->registrationStatus = $registrationStatus;
    }



    public function __construct()
    {
        $this->relatedRegistrationList = new ArrayCollection();
    }

    static public function getPersonByEmailAddress (EntityManager $em, $EmailAddress) {
        $personRepo     = $em->getRepository('TwenchaBundle:person');
        $response       = $personRepo->findOneBy(array(
            'emailAddress' => $EmailAddress
        ));

        if (isset($reponse)) {
            $response->setEm ($em);
        }

        return $response;
    }

    /**
     * @param EntityManager $em
     * @param $parameters
     * @return person
     */
    static public function getPerson (EntityManager $em, $parameters) {
        $response     = $em->getRepository('TwenchaBundle:person')
            ->findOneBy($parameters);

        return $response;
    }

    static public function getOrCreatePersonByMobile (EntityManager $em, $mobileNo) {
        $response = person::getPerson ($em, array (
            'mobile'        => $mobileNo
        ));

        if ($response == NULL) {
            // create person
            $response = person::createPerson($em, $mobileNo);

            $em->persist($response);
            $em->flush();
        }

        return $response;
    }

    /**
     * @param EntityManager $em
     * @param $email
     * @return person
     */
    static public function findOrCreatePersonByEmail (EntityManager $em, $email) {
        $response = person::getPerson ($em, array (
            'emailAddress'     => $email
        ));

        if ($response == NULL) {
            // create person
            $response = person::createPerson($email);

            $em->persist($response);
            $em->flush();
        }

        return $response;
    }

    static public function createPerson ($emailAddress = NULL) {
        $response = new person ();
        $response->setEmailAddress($emailAddress);
        $response->setRegistrationStatus(person::NO_EMAIL_PROVIDED);

        return $response;
    }

    public function __toString() {
        return $this->getEmailAddress();
    }
}