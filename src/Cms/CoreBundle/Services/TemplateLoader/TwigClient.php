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

    /**
     * code with no white spaces. Differs from rawCode as raw code has white spaced but does not have includes or use blocks
     */
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
        // also need to trim "
        return trim($this->getOneInner($this->getStrippedCode(), '{%extends', '%}'), "'");
    }

    /**
     * What is being included via Twig's "use" method of this code?
     *
     * @return array
     */
    public function getUses()
    {
        // also need to trim "
        return $this->getAllInner($this->getStrippedCode(), '{%use', '%}');
    }

    public function getRawCode()
    {
        $rawCode = $this->stripExtends($this->getCode());
        return $this->stripUse($rawCode);
    }

    public function stripUse($code)
    {
//        $code = str_replace('{%use', '{% use', $code); CANT USE THIS BC YOU CAN STILL ADD ARBITRARY SPACE
        $code =  preg_replace('/' . preg_quote('{% use') .
            '.*?' .
            preg_quote('%}') . '/', '', $code);
        return trim($code);
    }

    public function stripExtends($code)
    {
//        $code = str_replace('{%extends', '{% extends', $code); SEE ABOVE
        $code = preg_replace('/' . preg_quote('{% extends') .
            '.*?' .
            preg_quote('%}') . '/', '', $code);
        return trim($code);
    }

    /**
     * Returns a string with an exception message if invalid or bool true if valid. Check with !== true.
     *
     * @param string $code
     * @return string|bool
     */
    public function validate($code)
    {
        try{
            $this->twig->parse($this->twig->tokenize($code));
        }catch(\Twig_Error_Syntax $e){
            return 'Twig Error: '.$e->getMessage();
        }
        return true;
    }

    /**
     * Checks if site has access to a given template name
     *
     * @param Cms\CoreBundle\Document\Site $site
     * @param $templateName
     * @return bool
     */
    public function siteHasTemplateAccess(\Cms\CoreBundle\Document\Site $site, $templateName)
    {
        return $site->hasTemplateName($templateName) ? true : false;
    }

    /**
     * Ensure site has access. Returns bool true if access is granted and a string with an error message if access is denied. Check with !== true.
     *
     * @param \Cms\CoreBundle\Document\Site $site
     * @param $extends
     * @param array $uses
     * @return bool|string
     */
    public function siteHasAccessExtendsAndUses(\Cms\CoreBundle\Document\Site $site, $extends, array $uses)
    {
        if ( ! $this->siteHasTemplateAccess($site, $extends) )
        {
            return 'Cannot extend '.$extends.' because '.$site->getName().' does not have access to '.$extends;
        }
        foreach ($uses as $use) {
            if ( ! $this->siteHasTemplateAccess($site, $use) )
            {
                return 'Cannot use '.$use.' because '.$site->getName().' does not have access to '.$use;
            }
        }
        return true;
    }

    /**
     * Creates code content out of rawCode, extends and uses
     *
     * @param $code
     * @param string $extends
     * @param array $uses
     * @return string
     */
    public function createCode($code, $extends = '', array $uses = array())
    {
        if ( ! empty($uses) )
        {
            foreach ($uses as $use) {
                $code = "{% use '".$use."' %} \n ".$code;
            }
        }
        if ( $extends )
        {
            $code = "{% extends '".$extends."' %}\n ".$code;
        }
        return $code;
    }

}