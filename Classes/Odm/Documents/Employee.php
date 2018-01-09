<?php
/**
 * Created by PhpStorm.
 * User: aonurdemir
 * Date: 8.01.2018
 * Time: 11:06
 */

namespace Classes\Odm\Documents;
use Classes\Odm\Documents\Base\BaseEmployee;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Employee extends BaseEmployee
{
    /** @ODM\ReferenceOne(targetDocument="Classes\Odm\Documents\Manager") */
    private $manager;

    public function getManager() { return $this->manager; }
    public function setManager(Manager $manager) { $this->manager = $manager; }
}