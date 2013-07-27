<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 9:38 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

/**
 * Class TwigEnvironment
 * @package Cms\CoreBundle\Services\TemplateLoader
 */
class TwigEnvironment {

    /**
     * @var implements Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var
     */
    private $cacheDir;

    /**
     * @param $kernal
     */
    public function __construct($kernal)
    {
        $this->cacheDir = $kernal->getRootDir().'/cache/client/twig';
    }

    /**
     * @param  $cacheDir
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return void
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @param \Cms\CoreBundle\Services\TemplateLoader\implements $loader
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return \Cms\CoreBundle\Services\TemplateLoader\implements
     */
    public function getLoader()
    {
        return $this->loader;
    }

    public function load()
    {
        return new \Twig_Environment($this->loader, array('cache' => $this->cacheDir, 'auto_reload' => true));
    }

}