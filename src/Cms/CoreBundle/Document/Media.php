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
class Media extends Base {

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
     * @MongoDB\String
     */
    private $ext;

    /**
     * @MongoDB\Int
     */
    private $size;

    /**
     * @MongoDB\String
     */
    private $author;

    /**
     * @MongoDB\String
     */
    private $alt;

    /**
     * @MongoDB\Hash
     */
    private $metadata;

    /**
     * @var array $file
     */
    private $file;

    /**
     * @MongoDB\Collection
     */
    private $contentTypeIds;

    public function __construct()
    {
        $this->metadata = array();
        $this->contentTypeIds = array();
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
        $this->setExt(pathinfo($filename, PATHINFO_EXTENSION));
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
     * Set alt
     *
     * @param string $alt
     * @return self
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }

    /**
     * Get alt
     *
     * @return string $alt
     */
    public function getAlt()
    {
        return $this->alt;
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
     * @param null $key
     * @return array $metadata
     */
    public function getMetadata($key = null)
    {
        if ( ! isset($key) )
        {
            return $this->metadata;
        }
        if ( isset($this->metadata[$key]) )
        {
            return $this->metadata[$key];
        }
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
        if ( isset($file['tmp_name']) )
        {
            $this->setUrl($file['tmp_name']);
            if ( $this->getMime() === 'image/jpeg' OR $this->getMime() === 'image/jpg' OR $this->getMime() === 'image/png' OR $this->getMime() === 'image/gif' )
            {
                $imageSizeArray = getimagesize($file['tmp_name']);
                if ( isset($imageSizeArray[3]) )
                {
                    $this->setMetadata(array('dimensions' => $imageSizeArray[3]));
                }
            }
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

    /**
     * Add a content type id
     *
     * @param $contentTypeId
     * @return $this
     */
    public function addContentTypeId($contentTypeId)
    {
        if ( ! \is_string($contentTypeId) OR in_array($contentTypeId, $this->contentTypeIds) )
        {
            return $this;
        }
        $this->contentTypeIds[] = $contentTypeId;
        return $this;
    }

    public function removeContentTypeId($contentTypeId)
    {
        if ( ! \is_string($contentTypeId) )
        {
            return $this;
        }
        $keys = \array_keys($this->contentTypeIds, $contentTypeId);
        foreach ($keys as $key)
        {
            unset($this->contentTypeIds[$key]);
            $this->contentTypeIds = array_values($this->contentTypeIds);
        }
        return $this;
    }

    /**
     * Get contentTypeIds
     *
     * @return array contentTypeIds
     */
    public function getContentTypeIds()
    {
        return $this->contentTypeIds;
    }

}
