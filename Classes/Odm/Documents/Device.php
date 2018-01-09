<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 8.01.2018
 * Time: 14:17
 */

namespace Classes\Odm\Documents;

use Classes\Odm\Documents\Embedded\Coordinates;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 * @ODM\Index(keys={"lastLocation"="2d"})
 */

class Device
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $alias;

    /** @ODM\Field(type="collection") @ODM\UniqueIndex(order="asc")*/
    private $bluetoothIds;

    /** @ODM\Field(type="string") */
    private $version;

    /** @ODM\Field(type="date") */
    private $updatedAt;

    /** @ODM\Field(type="date") */
    private $createdAt;

    /** @ODM\Field(type="string") */
    private $assetType;

    /** @ODM\EmbedOne(targetDocument="Classes\Odm\Documents\Embedded\Coordinates") */
    public $lastLocation;

    /** @ODM\ReferenceOne(targetDocument="Classes\Odm\Documents\User") */
    private $owner;

    public function __construct()
    {
        $this->bluetoothIds = array();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getBluetoothIds()
    {
        return $this->bluetoothIds;
    }

    /**
     * @param mixed $bluetoothId
     */
    public function addBluetoothId($bluetoothId)
    {
        $this->bluetoothIds[] = $bluetoothId;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
    public function getAssetType()
    {
        return $this->assetType;
    }

    /**
     * @param mixed $assetType
     */
    public function setAssetType($assetType)
    {
        $this->assetType = $assetType;
    }

    /**
     * @return mixed
     */
    public function getLastLocation()
    {
        return $this->lastLocation;
    }

    /**
     * @param Coordinates $lastLocation
     */
    public function setLastLocation(Coordinates $lastLocation)
    {
        $this->lastLocation = $lastLocation;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }
}
