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
// todo: get code that matches user email address to person email address from anchorcards app - at time of writing this code is not yet finished.
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

    /*
     * var BasePerson
     *
     * @ORM\OneToOne(targetEntity="VisageFour\Bundle\PersonBundle\Entity\BasePerson", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="related_person_id", referencedColumnName="id", unique=true, nullable=true)
     * })
     */
    //private $relatedPerson;

    // todo: when this class is implemented in a final entity, the $relatedPerson must be defined
    // use the above code but remember to add ath double star to begin / activate the code in the inheriting class: **
    // remember to add getters and setters


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}