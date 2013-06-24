<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 4:49 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Asset
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="assets")
 */
class Asset {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $ext;

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\Hash
     */
    private $acl;

    /**
     * @MongoDB\String
     */
    private $url;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ext
     *
     * @param string $ext
     * @return self
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     * Get ext
     *
     * @return string $ext
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set acl
     *
     * @param collection $acl
     * @return self
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
        return $this;
    }

    /**
     * Get acl
     *
     * @return collection $acl
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

}
