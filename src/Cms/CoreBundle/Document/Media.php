<?php
/**
 * User: Brian Anderson
 * Date: 6/25/13
 * Time: 6:47 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Media
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="media", repositoryClass="Cms\CoreBundle\Repository\mediaRepository")
 */
class Media {

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
    private $filename;

    /**
     * @MongoDB\String
     */
    private $storage;

    /**
     * @MongoDB\String
     */
    private $url;

    /**
     * @MongoDB\String
     */
    private $mime;

    /**
     * @MongoDB\Int
     */
    private $size;

    /**
     * @MongoDB\String
     */
    private $author;

    /**
     * @MongoDB\Hash
     */
    private $metadata;

    /**
     * @var array $file
     */
    private $file;

    public function __construct()
    {
        $this->metadata = array();
    }


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
     * Set filename
     *
     * @param string $filename
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string $filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set storage
     *
     * @param string $storage
     * @return self
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Get storage
     *
     * @return string $storage
     */
    public function getStorage()
    {
        return $this->storage;
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
     * Set mime
     *
     * @param string $mime
     * @return self
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
        return $this;
    }

    /**
     * Get mime
     *
     * @return string $mime
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set size
     *
     * @param int $size
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return int $size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return string $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     * @return self
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Get metadata
     *
     * @return array $metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set file array (one element from php $_FILES global array)
     *
     * @param array $file
     * @return $this
     */
    public function setFile(array $file)
    {
        $this->file = $file;
        if ( isset($file['name']) )
        {
            $this->setFilename($file['name']);
        }
        if ( isset($file['type']) )
        {
            $this->setMime($file['type']);
        }
        if ( isset($file['size']) )
        {
            $this->setSize($file['size']);
        }
        return $this;
    }

    /**
     * Get file array
     *
     * @return array file
     */
    public function getFile()
    {
        return $this->file;
    }

}
