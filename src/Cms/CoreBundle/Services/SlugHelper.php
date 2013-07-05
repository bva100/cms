<?php
/**
 * User: Brian Anderson
 * Date: 7/4/13
 * Time: 4:20 PM
 */

namespace Cms\CoreBundle\Services;


/**
 * Class SlugHelper
 * @package Cms\CoreBundle\Services
 */
class SlugHelper {

    /**
     * @var string $fullSlug
     */
    private $fullSlug;

    private $slugArray;

    /**
     * @param string $fullSlug
     * @return $this
     */
    public function setFullSlug($fullSlug)
    {
        $this->fullSlug = $fullSlug;
        $this->createSlugArray();
        return $this;
    }

    /**
     * @return string
     */
    public function getFullSlug()
    {
        return $this->fullSlug;
    }

    /**
     * Get the slug prefix
     *
     * @return string
     * @throws \Exception
     */
    public function getSlugPrefix()
    {
        if ( ! isset($this->slugArray[0]) )
        {
            throw new \Exception('Must set full slug and run the createSlugArray method before getting the slug prefix');
        }
        return $this->slugArray[0];
    }

    /**
     * Get slug "affix"
     *
     * @return null
     */
    public function getSlug()
    {
        return ltrim(str_replace($this->getSlugPrefix(), '', $this->fullSlug), '/');
    }

    /**
     * @return $this
     */
    private function createSlugArray()
    {
        $this->slugArray = explode('/', $this->fullSlug);
    }

    /**
     * @return array
     */
    public function getSlugArray()
    {
        return $this->slugArray;
    }


}