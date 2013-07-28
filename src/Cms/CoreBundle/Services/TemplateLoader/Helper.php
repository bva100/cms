<?php
/**
 * User: Brian Anderson
 * Date: 7/28/13
 * Time: 5:46 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

class Helper {

    /**
     * Code without extends or uses
     *
     * @var string
     */
    private $rawCode;

    /**
     * Raw code without whitespaces
     *
     * @var string
     */
    private $nonWhiteSpaceCode;

    /**
     * Raw code is template code which typically does not contain use or extend statements
     *
     * @param $rawCode
     * @return $this
     */
    public function setRawCode($rawCode)
    {
        $this->rawCode = $rawCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawCode()
    {
        return $this->rawCode;
    }

    /**
     * Removes all white spaces from rawCode before setting. Not a public method as getter implements lazy setting.
     *
     * @return $this
     */
    private function setNonWhiteSpaceCode()
    {
        $this->nonWhiteSpaceCode = preg_replace('/\s+/', '', $this->rawCode);
        return $this;
    }

    /**
     * Returns rawCode without white spaces. Implements lazy setting (no need to call setNonWhiteSpaceCode() method).
     * Must set rawCode before calling this method.
     *
     * @return string
     */
    public function getNonWhiteSpaceCode()
    {
        if ( ! $this->nonWhiteSpaceCode )
        {
            $this->setNonWhiteSpaceCode();
        }
        return $this->nonWhiteSpaceCode;
    }

    /**
     * Checks if non white space code contains extends block. Returns bool.
     * Can be called on its own but is also included in validate method.
     *
     * @return bool
     */
    public function containsExtends()
    {
        if ( strpos($this->getNonWhiteSpaceCode(), '{%extends') !== false )
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Checks if non white space code contains uses block. Returns bool.
     * Can be called on its own but is also included in validate method.
     *
     * @return bool
     */
    public function containsUses()
    {
        if ( strpos($this->getNonWhiteSpaceCode(), '{%uses') !== false )
        {
            return true;
        }else{
            return false;
        }
    }

}