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
     * @var \Twig_Environment twigEnvironment;
     */
    private $twigEnvironment;

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
     * @param array $params
     * @return mixed|string
     */
    public function getRawCode(array $params = array())
    {
        if ( in_array('removeParents', $params) )
        {
            return str_replace('{{ parent() }}', '', $this->rawCode);
        }
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
     * @param \Twig_Environment $twigEnvironment
     * @return $this
     */
    public function setTwigEnvironment($twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
        return $this;
    }

    /**
     * @return \Twig_Environment string
     */
    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
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
        if ( strpos($this->getNonWhiteSpaceCode(), '{%use') !== false )
        {
            return true;
        }else{
            return false;
        }
    }

    public function validIncludeName($name)
    {
        if(preg_match('/^[a-zA-Z\d:-]+$/', $name) === 1)
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Returns an array with status and message indices. If syntax is invalid, status index will be false.
     *
     * @return array
     */
    public function validateTwigSyntax()
    {
        $twig = $this->getTwigEnvironment()->load();
        try{
            $twig->parse($twig->tokenize($this->getRawCode(array('removeParents'))));
        }catch(\Twig_Error_Syntax $e){
            return array('status' => false, 'message' => 'Twig Error: '.$e->getMessage());
        }
        return array('status' => true, 'message' => 'twig syntax is valid');
    }

    /**
     * Checks if raw code is valid. Returns an array with status and message indices. If invalid, status index will be false
     *
     * @return array
     */
    public function validate()
    {
        $validSyntax = $this->validateTwigSyntax();
        if ( $validSyntax['status'] === false )
        {
            return $validSyntax;
        }
        if ( $this->containsUses() )
        {
            return array('status' => false, 'message' => 'Code cannot contain the "use" statement. To include another template, please add via the inheritance section.');
        }
        if ( $this->containsExtends() )
        {
            return array('status' => false, 'message' => 'Code cannot contain the "extends" statement. To include another template, please add via the inheritance section.');
        }
        return array('status' => true, 'message' => 'Code is valid');
    }

    /**
     * Create code from injected rawCode, and params extends and uses. extends and use takes no syntax. Validates by default.
     *
     * @param $extends
     * @param array $uses
     * @return array
     */
    public function createCode($extends, array $uses = array())
    {
        $validRawCodeArray = $this->validate();
        if ( $validRawCodeArray['status'] === false )
        {
            return $validRawCodeArray;
        }
        $code = $this->getRawCode();
        if ( ! empty($uses) )
        {
            foreach ($uses as $use) {
                if ( $this->validIncludeName($use) )
                {
                    $code = "{% use '".$use."' %}".$code;
                }
            }
        }
        if ( $extends AND $this->validIncludeName($extends) )
        {
            $code = "{% extends '".$extends."' %}".$code;
        }
        return array('status' => true, 'message' => 'success', 'code' => $code);
    }

}