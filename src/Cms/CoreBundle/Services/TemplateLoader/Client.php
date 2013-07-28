<?php
/**
 * User: Brian Anderson
 * Date: 7/28/13
 * Time: 9:41 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;


class Client {

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $nonWhiteSpaceCode;

    /**
     * set code
     *
     * @param $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Removes all white spaces from code before setting. Not a public method as getter implements lazy setting.
     *
     * @return $this
     */
    private function setNonWhiteSpaceCode()
    {
        $this->nonWhiteSpaceCode = preg_replace('/\s+/', '', $this->code);
        return $this;
    }

    /**
     * Returns code without white spaces. Implements lazy setting (no need to call setNonWhiteSpaceCode() method).
     * Must set code before calling this method.
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
     * Get the first occurrence of the inner string found in between a starting string and ending string
     *
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    public function getOneInner($string, $start, $end)
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

    /**
     * Get an array of all inner strings found between a starting string and ending string
     *
     * @param $string
     * @param $start
     * @param $end
     * @return array
     */
    public function getAllInner($string, $start, $end)
    {
        $array = array();
        while($this->getOneInner($string, $start, $end) != false){
            $result = $this->getOneInner($string, $start, $end);
            $array[] = trim(trim($result, "'"), '"');
            $line = $start.$result.$end;
            $string = str_replace($line, '', $string);
        }
        return $array;
    }

    /**
     * Returns the name of the template which is being extended, if one exists. If none found, returns empty string.
     *
     * @return string
     */
    public function getExtends()
    {
        return trim(trim($this->getOneInner($this->getNonWhiteSpaceCode(), '{%extends', '%}'), "'"), '"');
    }

    /**
     * Returns an array of template names which are include in this code. If none found, return empty array
     *
     * @return array
     */
    public function getUses()
    {
        return $this->getAllInner($this->getNonWhiteSpaceCode(), '{%use', '%}');
    }

    /**
     * Returns an array with indices extends, uses, and rawCode
     * Raw code is all code without include statements
     *
     * @return array
     */
    public function getComponents()
    {
        $array = array();
        $array['extends'] = $this->getExtends();
        $array['uses'] = $this->getUses();
        $array['rawCode'] = $this->getCode();
        if ( $array['extends'] )
        {
            $array['rawCode'] = str_replace("{% extends '".$array['extends']."' %}", '', $array['rawCode']);
        }
        if ( ! empty($array['uses']) )
        {
            foreach ($array['uses'] as $uses)
            {
                $array['rawCode'] = str_replace("{% use '".$uses."' %}", '', $array['rawCode']);
            }
        }
        return $array;
    }
}