<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 8.01.2018
 * Time: 13:44
 */

namespace Classes\Odm\Documents;

use Doctrine\Common\Collections\ArrayCollection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
    /** @ODM\Id */
    private $id;
    /** @ODM\Field(type="string") @ODM\UniqueIndex(order="asc") */
    private $username;
    /** @ODM\Field(type="string") */
    private $password;
    /** @ODM\Field(type="string") */
    private $firstName;
    /** @ODM\Field(type="string") */
    private $lastName;
    /** @ODM\Field(type="date") */
    private $createdAt;
    /** @ODM\ReferenceMany(targetDocument="Classes\Odm\Documents\Device") */
    private $devices = array();

    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param mixed $device
     */
    public function addDevices($device)
    {
        $this->devices[] = $device;
    }
}
