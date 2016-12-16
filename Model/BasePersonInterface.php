<?php

/**
 * Created by PhpStorm.
 * User: CameronBurns
 * Date: 23/05/2016
 * Time: 4:03 PM
 */

namespace VisageFour\Bundle\PersonBundle\Model;

use VisageFour\Bundle\PersonBundle\Entity\BaseUser;

interface BasePersonInterface
{
    public function getId ();

    public function setCreatedAt ($createdAt);
    public function getCreatedAt ();

    public function setUpdatedAt ($updatedAt);
    public function getUpdatedAt ();

    public function setTitle ($title);
    public function getTitle ();

    public function setFirstName ($firstName);
    public function getFirstName ();

    public function setLastName ($lastName);
    public function getLastName ();

    public function setMobileNumber ($mobileNumber);
    public function getMobileNumber ();

    public function setEmail ($email);
    public function getEmail ();

    public function setSuburb ($suburb);
    public function getSuburb ();

    public function setCity ($city);
    public function getCity ();

    public function setCountry ($country);
    public function getCountry ();
}