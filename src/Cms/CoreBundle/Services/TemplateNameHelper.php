<?php
/**
 * User: Brian Anderson
 * Date: 6/20/13
 * Time: 9:42 PM
 */

namespace Cms\CoreBundle\Services;


use InvalidArgumentException;

/**
 * Class TemplateNameHelper
 * @package Cms\CoreBundle\Services
 */
class TemplateNameHelper {

    /**
     * @var string
     */
    private $templateName;

    /**
     * @var array
     */
    private $templateNameArray;

    /**
     * @param string $templateName
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setName($templateName)
    {
        if ( is_string($templateName) )
        {
            $this->templateName = $templateName;
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
    public function getName()
    {
        return $this->templateName;
    }

    /**
     * @return $this
     */
    public function createTemplateNameArray()
    {
        $this->templateNameArray =  explode(':', $this->templateName);
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        $namespace = $this->templateNameArray[0];
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
        return $this->templateNameArray[1];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->templateNameArray[2];
    }
}