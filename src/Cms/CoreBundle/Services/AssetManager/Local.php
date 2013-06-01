<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 12:57 PM
 */

namespace Cms\CoreBundle\Services\AssetManager;

use Cms\CoreBundle\Exceptions\AssetManagerException;


/**
 * Class AssetManager
 * @package Cms\CoreBundle\Services
 */
class Local extends AbstractAssetManager {

    /**
     * @param array $dirs
     */
    public function setDirs(array $dirs = array())
    {
        if ( empty($dirs) )
        {
            $root = __DIR__.'/../../../ClientAssets/';
            $dirs = array(
                'css' => $root.'css/',
                'js'  => $root.'js/',
            );
        }
        $this->dirs = $dirs;
    }

    /**
     * @param $name
     * @param $ext
     * @param $data
     * @throws AssetManagerException
     */
    public function save($name, $ext, $data)
    {
        $this->validateName($name);
        $results = \file_put_contents( $this->getDir($ext).'/'.$name.'.'.$ext, $data, LOCK_EX );
        if ( $results === false )
        {
            throw new AssetManagerException('File write failed. Please check permissions.');
        }
    }

    /**
     * @param $name
     * @param $ext
     * @return string
     */
    public function read($name, $ext)
    {
        $this->validateName($name);
        return \file_get_contents( $this->getDir($ext).$name.'.'.$ext );
    }

    /**
     * @param $name
     * @param $ext
     * @throws AssetManagerException
     */
    public function delete($name, $ext)
    {
        $this->validateName($name);
        if ( ! \unlink( $this->getDir($ext).$name.'.'.$ext ) )
        {
            throw new AssetManagerException('File deletion failed. Please check permissions');
        }
    }

}