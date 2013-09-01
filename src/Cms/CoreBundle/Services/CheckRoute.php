<?php
/**
 * User: Brian Anderson
 * Date: 8/31/13
 * Time: 5:37 PM
 */
namespace Cms\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Request;

class CheckRoute {

    private $hostnames;

    private $request;

    public function __construct()
    {
        $this->setHostnames();
    }

    public function setHostnames(array $hostnames = array('localhost', 'dev.pipestack.com', 'staging.pipestack.com', 'www.pipestack.com', 'pipestack.com'))
    {
        $this->hostnames = $hostnames;
        return $this;
    }

    public function getHostnames()
    {
        return $this->hostnames;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function isClient()
    {
        if ( in_array($_SERVER['HTTP_HOST'], $this->hostnames) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}