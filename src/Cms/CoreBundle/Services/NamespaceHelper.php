<?php
/**
 * User: Brian Anderson
 * Date: 6/20/13
 * Time: 9:42 PM
 */

namespace Cms\CoreBundle\Services;

use InvalidArgumentException;

/**
 * Class NameHelper
 * @package Cms\CoreBundle\Services
 */
class NamespaceHelper {

    /**
     * @var string
     */
    private $fullname;

    /**
     * @var array
     */
    private $nameArray;

    /**
     * @param string $fullname
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setFullname($fullname)
    {
        if ( is_string($fullname) )
        {
            $this->fullname = $fullname;
            $this->createTemplateNameArray();
            return $this;
        }
        else
        {
            throw new InvalidArgumentException('templateName must be a string');
        }
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @return $this
     */
    public function createTemplateNameArray()
    {
        $this->nameArray =  explode(':', $this->fullname);
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        $namespace = $this->nameArray[0];
        if ( strpos($namespace, '-') !== false )
        {
            $namespace = strstr($namespace, '-', true);
        }
        return $namespace;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->nameArray[1];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->nameArray[2];
    }

    /**
     * Asset name is equivalent to type
     *
     * @return sting
     */
    public function getAssetName()
    {
        return $this->getType();
    }

}