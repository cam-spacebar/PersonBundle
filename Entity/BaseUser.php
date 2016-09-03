<?php

namespace VisageFour\Bundle\PersonBundle\Entity;

use FOS\UserBundle\FOSUserBundle;
use FOS\UserBundle\Model\User as FOSUser;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use VisageFour\Bundle\PersonBundle\Entity\BasePerson;

/**
 * User
 *
 * @ORM\Table(name="appuser")
 * @ORM\Entity(repositoryClass="Platypuspie\AnchorcardsBundle\Repository\UserRepository")
 */
class BaseUser extends FOSUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"zapierSpreadsheet"})
     */
    protected $id;

    /**
     * @var BasePerson
     *
     * @ORM\OneToOne(targetEntity="VisageFour\Bundle\PersonBundle\Entity\BasePerson\BasePerson", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="related_person_id", referencedColumnName="id", unique=true, nullable=true)
     * })
     */
    private $relatedPerson;

    /**
     * @return BasePerson
     */
    public function getRelatedPerson()
    {
        return $this->relatedPerson;
    }

    /**
     * @param BasePerson $relatedPerson
     */
    public function setRelatedPerson($relatedPerson)
    {
        $this->relatedPerson = $relatedPerson;
    }


}