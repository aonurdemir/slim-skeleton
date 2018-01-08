<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 8.01.2018
 * Time: 11:07
 */


namespace Classes\Odm\Documents\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Classes\Odm\Documents\Address;
use DateTime;

/** @ODM\MappedSuperclass */
abstract class BaseEmployee
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="int", strategy="increment") */
    private $changes = 0;

    /** @ODM\Field(type="collection") */
    private $notes = array();

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="int") */
    private $salary;

    /** @ODM\Field(type="date") */
    private $started;

    /** @ODM\Field(type="date") */
    private $left;

    /** @ODM\EmbedOne(targetDocument="Address") */
    private $address;

    public function getId() { return $this->id; }

    public function getChanges() { return $this->changes; }
    public function incrementChanges() { $this->changes++; }

    public function getNotes() { return $this->notes; }
    public function addNote($note) { $this->notes[] = $note; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getSalary() { return $this->salary; }
    public function setSalary($salary) { $this->salary = (int) $salary; }

    public function getStarted() { return $this->started; }
    public function setStarted(DateTime $started) { $this->started = $started; }

    public function getLeft() { return $this->left; }
    public function setLeft(DateTime $left) { $this->left = $left; }

    public function getAddress() { return $this->address; }
    public function setAddress(Address $address) { $this->address = $address; }
}