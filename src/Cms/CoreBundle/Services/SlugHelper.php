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
     * @var string
     */
    private $fullSlug;

    /**
     * @var array
     */
    private $slugArray;

    /**
     * @var string
     */
    private $title;

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

    /**
     * Set title
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $title;
    }

    /**
     * Is this a titled slug?
     *
     * @return bool
     */
    public function isTitleSlug()
    {
        if ( ! isset($this->title) )
        {
            return false;
        }
        $sluggedTitle = strtolower(str_replace(' ', '-', $this->title));
        return $sluggedTitle == $this->getSlug() ? true: false;
    }

}