<?php

namespace VisageFour\Bundle\PersonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Platypuspie\AnchorcardsBundle\Entity\Photographer;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;

use Doctrine\ORM\Mapping\DiscriminatorMap;

use Symfony\Component\Serializer\Annotation\Groups;

//...iminatorMap({"baseperson" = "BasePerson", "person" = "Twencha\TwenchaBundle\Entity\person" })
//...iminatorMap({"baseperson" = "BasePerson", "photographer" = "Photographer" })
/**
 * BasePerson
 *
 * @ORM\Table(name="BasePerson")
 * @ORM\Entity(repositoryClass="VisageFour\Bundle\PersonBundle\Repository\basePersonRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"baseperson" = "BasePerson", "photographer" = "Platypuspie\AnchorcardsBundle\Entity\Photographer" })
 *
 * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 */
class BasePerson extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"zapierSpreadsheet"})
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="CreatedAt", type="datetime")
     * @Groups({"zapierSpreadsheet"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="UpdatedAt", type="datetime")
     * @Groups({"zapierSpreadsheet"})
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=5, nullable=true)
     * @Assert\Choice(
     *     choices = { "mr", "ms", "mrs" },
     *     message = "Choose a valid title"
     * )
     * @Groups({"zapierSpreadsheet"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=20, nullable=true)
     * @Groups({"zapierSpreadsheet"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=20, nullable=true)
     * @Groups({"zapierSpreadsheet"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="mobileNumber", type="string", length=75, unique=false, nullable=true)
     * @Groups({"zapierSpreadsheet"})
     */
    private $mobileNumber;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2,max=70)
     * @Groups({"zapierSpreadsheet"})
     */
    private $suburb;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2,max=50)
     * @Groups({"zapierSpreadsheet"})
     */
    private $city;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2,max=50)
     * @Groups({"zapierSpreadsheet"})
     */
    private $country;

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
     * @return BasePerson
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
     * @return BasePerson
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
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getSuburb()
    {
        return $this->suburb;
    }

    /**
     * @param string $suburb
     */
    public function setSuburb($suburb)
    {
        $this->suburb = $suburb;
    }

    public function __construct () {
        parent::__construct();
    }
}