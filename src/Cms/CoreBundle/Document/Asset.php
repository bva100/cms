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
     * @MongoDB\Collection
     */
    private $history;

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

    /**
     * @param $contentStr
     * @param null $timestamp
     */
    public function addHistory($contentStr, $timestamp = null)
    {
        if ( ! $timestamp )
        {
            $timestamp = time();
        }
        if ( ! $this->history )
        {
            $this->history = array();
        }
        if ( count($this->history) >= 50 )
        {
            $this->removeOldestHistory();
        }

        $entry = new \stdClass;
        $entry->created = $timestamp;
        $entry->content = $contentStr;
        $this->history[] = $entry;
    }

    /**
     * Get history
     *
     * @return collection $history
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @return void
     */
    public function removeAllHistory()
    {
        $this->history = array();
    }

    /**
     * @return void
     */
    public function removeOldestHistory()
    {
        if ( isset($this->history) )
        {
            \array_pop($this->history);
        }
    }

    /**
     * @return void
     */
    public function removeNewestHistory()
    {
        if ( isset($this->history) )
        {
            \array_shift($this->history);
        }
    }

    /**
     * Set history
     *
     * @param collection $history
     * @return self
     */
    public function setHistory($history)
    {
        $this->history = $history;
        return $this;
    }
}
