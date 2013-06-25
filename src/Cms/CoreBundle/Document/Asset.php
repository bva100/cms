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
 * @MongoDB\Document(collection="assets", repositoryClass="Cms\CoreBundle\Repository\assetRepository")
 */
class Asset {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $siteId;

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
     * @MongoDB\String
     */
    private $content;

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
     * Set siteId
     *
     * @param string $siteId
     * @return self
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * Get siteId
     *
     * @return string $siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setName($name)
    {
        if ( preg_match('/[^a-z_\-0-9]/i', str_replace(':', '', $name)) )
        {
            throw new \InvalidArgumentException('Invalid filename. Filename must be alphanumeric.');
        }
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
     * Set ext. In validation, only "css" and "js" (without beginning period), are accepted
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

    /**
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

}
