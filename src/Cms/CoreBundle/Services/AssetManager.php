<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 12:57 PM
 */

namespace Cms\CoreBundle\Services;

use Cms\CoreBundle\Exceptions\AssetManagerException;


/**
 * Class AssetManager
 * @package Cms\CoreBundle\Services
 */
class AssetManager {

    /**
     * @var array
     */
    private $dirs;

    /**
     * @param array $dirs
     */
    public function __construct(array $dirs = array())
    {
        $this->setDirs($dirs);
    }

    /**
     * @param $ext
     * @param $dir
     * @internal param $key
     * @internal param array $dirs
     */
    public function setDir($ext, $dir)
    {
        $this->dirs[$ext] = $dir;
    }

    /**
     * @param $ext
     * @return mixed
     */
    public function getDir($ext)
    {
        return $this->dirs[$ext];
    }

    /**
     * @param array $dirs
     */
    public function setDirs(array $dirs = array())
    {
        if ( empty($dirs) )
        {
            $root = __DIR__.'/../ClientAssets/';
            $dirs = array(
                'css' => $root.'css/',
                'js'  => $root.'js/',
            );
        }
        $this->dirs = $dirs;
    }

    /**
     * @return array
     */
    public function getDirs()
    {
        return $this->dirs;
    }

    /**
     * @param $name
     * @param $ext
     * @param $data
     * @throws AssetManagerException
     */
    public function save($name, $ext, $data)
    {
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
        return \file_get_contents( $this->getDir($ext).$name.'.'.$ext );
    }

    /**
     * @param $name
     * @param $ext
     * @throws \Cms\CoreBundle\Exceptions\AssetManagerException
     */
    public function delete($name, $ext)
    {
        if ( ! \unlink( $this->getDir($ext).$name.'.'.$ext ) )
        {
            throw new AssetmanagerException('File deletion failed. Please check permissions');
        }
    }

}