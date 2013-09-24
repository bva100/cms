<?php


namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

class Acl {

    /**
     * @MongoDB\String
     */
    private $id;

    /**
     * @MongoDB\Hash
     */
    private $owner;

    /**
     * @MongoDB\Hash
     */
    private $group;

    /**
     * @MongoDB\Hash
     */
    private $other;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $owner
     * @return $this
     * @throws \RuntimeException
     */
    public function setOwner(array $owner)
    {
        $this->validateValueObject($owner, 'owner');
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return array
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param array $group
     * @return $this
     */
    public function setGroup(array $group)
    {
        $this->validateValueObject($group, 'group');
        $this->group = $group;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array $other
     * @return $this
     * @throws \RuntimeException
     */
    public function setOther(array $other)
    {
        if ( ! isset($other['permissions']) ){
            throw new \RuntimeException('Other value object must include an array of permissions.');
        }
        $this->other = $other;
        return $this;
    }

    /**
     * @return array
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Validate value object data structure
     *
     * @param array $array
     * @param $name
     * @return bool
     * @throws \RuntimeException
     */
    public function validateValueObject(array $array, $name)
    {
        if ( ! isset($array['id']) ){
            throw new \RuntimeException(ucfirst($name).' value object must include a valid id.');
        }
        if ( ! isset($array['permissions']) ){
            throw new \RuntimeException(ucfirst($name).' value object must include an array of permissions.');
        }
        if ( ! is_array($array['permissions']) ){
            throw new \RuntimeException(ucfirst($name).' value object\'s permission key must have an array value.');
        }
        return true;
    }

}