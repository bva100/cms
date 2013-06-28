<?php
/**
 * User: Brian Anderson
 * Date: 6/26/13
 * Time: 12:08 AM
 */

namespace Cms\CoreBundle\Services\MediaManager;

use Cms\CoreBundle\Document\Media;

/**
 * Class Manager
 * @package Cms\CoreBundle\Services\MediaManager
 */
class Manager {

    /**
     * sdk for media storage source
     *
     * @var object $storage
     */
    private $storage;

    /**
     * S3 bucket/location
     *
     * @var string $bucket
     */
    private $bucket;

    /**
     * @var media $media
     */
    private $media;

    public function __construct(InterfaceStorage $storageFactory)
    {
        $this->setStorage($storageFactory->getStorage());
    }

    /**
     * @param object $storage
     * @return $this
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return object
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Set bucket
     *
     * @param $bucket
     * @return $this
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @param $bucket
     * @return string
     */
    public function getBucket($bucket)
    {
        return $this->bucket;
    }
    
    /**
     * @param $media
     * @return $this
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Persist a media entity to S3. To use reduced redundancy set first param to "REDUCED_REDUNDANCY"
     *
     * @param string $storageClass
     * @return bool|Media
     * @throws \Exception
     */
    public function persist($storageClass = 'STANDARD')
    {
        if ( ! isset($this->media) )
        {
            throw new \Exception('Media object must be injected prior to calling create');
        }
        $results = $this->storage->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $this->media->getFilename(),
            'SourceFile' => $this->media->getUrl(),
            'ACL' => 'public-read',
            'ContentType' => $this->media->getMime(),
            'StorageClass' => $storageClass,
            'Expires' => time() + 31536000,
            'CacheControl' => 'public',
        ));
        $resultsArray = $results->toArray();
        if ( isset($resultsArray['ObjectURL']) )
        {
            $this->media->setUrl($resultsArray['ObjectURL']);
            $this->media->setStorage('S3');
            return $this->media;
        }
        else
        {
            return false;
        }
    }

    public function delete()
    {
        if ( ! isset($this->media) )
        {
            throw new \Exception('Media object must be injected prior to calling create');
        }
        $this->storage->deleteObject(array(
            'Bucket' => $this->bucket,
            'Key' => $this->media->getFilename(),
        ));
    }
    
}