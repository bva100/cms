<?php
/**
 * User: Brian Anderson
 * Date: 7/27/13
 * Time: 8:24 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

class TwigClient {

    protected $twig;

    protected $code;

    protected $strippedCode;

    /**
     * Load and set twig environment
     */
    public function __construct()
    {
        $this->setTwig(new \Twig_Environment());
    }

    /**
     * Set Twig environment
     *
     * @param $twig
     * @return $this
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Set code
     *
     * @param string $code
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
     * Set code with no whitespace
     *
     * @return $this
     */
    public function setStrippedCode()
    {
        $this->strippedCode = preg_replace('/\s+/', '', $this->code);
        return $this;
    }

    /**
     * Get code with no whitespace. Sets stripped code if not yet set (lazy load).
     *
     * @return this
     */
    public function getStrippedCode()
    {
        if ( ! $this->strippedCode )
        {
            $this->setStrippedCode();
        }
        return $this->strippedCode;
    }

    /**
     * Get the first occurance of the inner string found in between a starting string and ending string
     *
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    private function getOneInner($string, $start, $end)
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
    private function getAllInner($string, $start, $end)
    {
        $array = array();
        while($this->getOneInner($string, $start, $end) != false){
            $result = $this->getOneInner($string, $start, $end);
            $array[] = trim($result, "'");
            $line = $start.$result.$end;
            $string = str_replace($line, '', $string);
        }
        return $array;
    }

    /**
     * What is being extended in this code?
     *
     * @return string
     */
    public function getExtends()
    {
        return trim($this->getOneInner($this->getStrippedCode(), '{%extends', '%}'), "'");
    }

    /**
     * What is being included via Twig's "use" method of this code?
     *
     * @return array
     */
    public function getUses()
    {
        return $this->getAllInner($this->getStrippedCode(), '{%use', '%}');
    }

    public function getRawCode()
    {
        $code = str_replace('{%use', '{% use', $this->getCode());
        $code = str_replace('{%extends', '{% extends', $code);
        $rawCode =  preg_replace('/' . preg_quote('{% use') .
            '.*?' .
            preg_quote('%}') . '/', '', $code);
        $rawCode = preg_replace('/' . preg_quote('{% extends') .
            '.*?' .
            preg_quote('%}') . '/', '', $rawCode);
        return trim($rawCode);
    }

    /**
     * Throws an exception if twig code is not valid. Guesses at what the problem is and where the line is. Returns code string if valid.
     *
     * @param array $params
     * @return string|bool
     */
    public function validate(array $params = array())
    {
        $this->twig->parse($this->twig->tokenize($this->code));
        return $this->code;
    }

}