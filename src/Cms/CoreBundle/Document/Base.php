<?php
/**
 * User: Brian Anderson
 * Date: 6/30/13
 * Time: 6:16 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Abstract class for Document entities
 *
 * Abstract Class Base
 * @package Cms\CoreBundle\Document
 * @MongoDB\MappedSuperclass
 */
Abstract class Base {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $state;

    /**
     * @MongoDB\Int
     */
    protected $created;

    /**
     * @MongoDB\Int
     */
    protected $updated;

    /**
     * @MongoDB\Int
     */
    protected $stateChanged;

    /**
     * @MongoDB\Hash
     */
    protected $acl;

    public function __construct()
    {
        $this->created = time();
        $this->setAcl();
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $stateChanged
     * @return $this
     */
    public function setStateChanged($stateChanged)
    {
        $this->stateChanged = $stateChanged;
        return $this;
    }

    /**
     * @return int
     */
    public function getStateChanged()
    {
        return $this->stateChanged;
    }

    /**
     * @param int $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set an Acl object manually. The more specific methods below are recommended.
     *
     * @param array $aclObject
     * @return $this
     */
    public function setAcl(array $aclObject = array())
    {
        if ( empty($aclObject) ){
            $aclObject = array(
                'owner' => array(),
                'group' => array(),
                'other' => array(),
            );
        }
        $this->acl = $aclObject;
        return $this;
    }

    /**
     * @param array $owner
     * @return $this
     * @throws \RuntimeException
     */
    public function setAclOwner(array $owner)
    {
        $this->validateAclObject($owner, 'owner');
        $this->acl['owner'] = $owner;
        return $this;
    }

    /**
     * Clear Acl object
     *
     * @return $this
     */
    public function removeAcl()
    {
        $this->setAcl();
        return $this;
    }

    /**
     * Get entire Acl object
     *
     * @return array
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return array
     */
    public function getAclOwner()
    {
        return $this->acl['owner'];
    }

    /**
     * @return string
     */
    public function getAclOwnerId()
    {
        return $this->acl['owner']['id'];
    }

    /**
     * @return array
     */
    public function getAclOwnerPermissions()
    {
        return $this->acl['owner']['permissions'];
    }

    /**
     * @param array $group
     * @return $this
     */
    public function setAclGroup(array $group)
    {
        $this->validateAclObject($group, 'group');
        $this->acl['group'] = $group;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAclGroup()
    {
        return $this->acl['group'];
    }

    /**
     * @return string
     */
    public function getAclGroupId()
    {
        return $this->acl['group']['id'];
    }

    /**
     * @return array
     */
    public function getAclGroupPermissions()
    {
        return $this->acl['group']['permissions'];
    }

    /**
     * @param array $other
     * @return $this
     * @throws \RuntimeException
     */
    public function setAclOther(array $other)
    {
        if ( ! isset($other['permissions']) ){
            throw new \RuntimeException('Other value object must include an array of permissions.');
        }
        $this->acl['other'] = $other;
        return $this;
    }

    /**
     * @return array
     */
    public function getAclOther()
    {
        return $this->acl->other;
    }

    /**
     * @return array
     */
    public function getAclOtherPermissions()
    {
        return $this->acl->other['permissions'];
    }

    /**
     * Validate value object data structure
     *
     * @param array $array
     * @param $name
     * @return bool
     * @throws \RuntimeException
     */
    public function validateAclObject(array $array, $name)
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
