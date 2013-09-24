<?php


namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Acl
 * @package Cms\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Acl {

    /**
     * @MongoDB\String
     */
    protected $id;

    /**
     * @MongoDB\Hash
     */
    protected $owner;

    /**
     * @MongoDB\Hash
     */
    protected $group;

    /**
     * @MongoDB\Hash
     */
    protected $other;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }



}