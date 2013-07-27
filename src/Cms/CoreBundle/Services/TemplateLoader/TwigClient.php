<?php
/**
 * User: Brian Anderson
 * Date: 7/27/13
 * Time: 8:24 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

class TwigClient {

    protected $twig;

    protected $type;

    public function __construct()
    {
        $this->setTwig(new \Twig_Environment());
    }

    public function setTwig($twig)
    {
        $this->twig = $twig;
        return $this;
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function validate($code, array $params = array())
    {
        try{
            $this->twig->parse($this->twig->tokenize($code));
        }catch (Twig_Error_Syntax $e){
            echo $e; die();
        }
        return $code;
    }

}