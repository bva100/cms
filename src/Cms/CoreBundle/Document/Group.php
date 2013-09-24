<?php


namespace Cms\CoreBundle\Document;

use Cms\CoreBundle\Document\Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Groups
 * @package Cms\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Group extends Base {

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\Hash
     */
    private $userIds;

    public function __construct()
    {
        $this->userIds = array();
        $this->created = time();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $userIds
     * @return $this
     */
    public function setUserIds(array $userIds)
    {
        $this->userIds = $userIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getUserIds()
    {
        return $this->userIds;
    }

    /**
     * Does group have user id?
     *
     * @param string $userId
     * @return bool
     */
    public function hasUserId($userId)
    {
        if ( ! is_string($userId) ){
            return false;
        }
        if ( ! in_array($userId, $this->userIds) ){
            return false;
        }
        return true;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function addUserId($userId)
    {
        if ( is_string($userId) AND  ! in_array($userId, $this->userIds) ){
            $this->userIds[] = $userId;
        }
        return $this;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function removeUserId($userId)
    {
        if ( ! is_string($userId) ){
            return $this;
        }
        $key = array_search($userId, $this->userIds);
        if ( $key !== false ){
            unset($this->userIds[$key]);
            $this->userIds = array_values($this->userIds);
        }
        return $this;
    }
    
}