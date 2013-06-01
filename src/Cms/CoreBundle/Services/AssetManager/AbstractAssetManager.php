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
abstract class AbstractAssetManager {

    /**
     * @var array
     */
    protected $dirs;

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
            $root = __DIR__.'/../../../ClientAssets/';
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
     * @throws \InvalidArgumentException
     */
    public function validateName($name)
    {
        if ( ! is_string($name) )
        {
            throw new \InvalidArgumentException('Filename must be a string to be valid');
        }
        if ( preg_match('/[^a-z_\-0-9]/i', $name) )
        {
            throw new \InvalidArgumentException('Invalid filename. Filename must be alphanumeric.');
        }
    }

    /**
     * @param $name
     * @param $ext
     * @param $data
     * @throws AssetManagerException
     */
    abstract public function save($name, $ext, $data);

    /**
     * @param $name
     * @param $ext
     * @return string
     */
    abstract public function read($name, $ext);

    /**
     * @param $name
     * @param $ext
     * @throws \Cms\CoreBundle\Exceptions\AssetManagerException
     */
    abstract public function delete($name, $ext);

}