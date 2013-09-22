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


}
