<?php
/**
 * User: Brian Anderson
 * Date: 6/25/13
 * Time: 11:51 PM
 */

namespace Cms\CoreBundle\Services\MediaManager;

use Aws\S3\S3Client;

/**
 * Class S3StorageFactory
 * @package Cms\CoreBundle\Services\MediaManager
 */
class S3StorageFactory implements InterfaceStorage {

    /**
     * @var array $credsArray
     */
    private $credsArray;

    /**
     * @param $key
     * @param $secret
     */
    public function __construct($key, $secret)
    {
        $this->setCredsArray($key, $secret);
    }

    /**
     * @param $key
     * @param $secret
     * @return $this
     */
    public function setCredsArray($key, $secret)
    {
        $this->credsArray = array('key' => $key, 'secret' => $secret);
        return $this;
    }

    /**
     * @return array
     */
    public function getCredsArray()
    {
        return $this->getCredsArray();
    }

    /**
     * Get the aws client object
     *
     * @return aws client
     * @throws \Exception
     */
    public function getStorage()
    {
        if ( ! isset($this->credsArray['key']) OR ! isset($this->credsArray['secret']) )
        {
            throw new \Exception('Creds array key and secret must be set prior to returning a storage object');
        }
        return S3Client::factory(array('key' => $this->credsArray['key'],'secret' => $this->credsArray['secret']));
    }

}