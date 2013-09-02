<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 11:25 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller {

    public function nodeReadV1Action($id)
    {
        $siteId = (string)$this->getSiteId($this->getRequest()->query->get('access_token'));
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' not found');
        }
        if ( $node->getSiteId() !== $siteId )
        {
            throw new \Exception('invalid access token');
        }
        return $this->output($node->getVars(), (string)$this->getRequest()->query->get('format'));
    }

    public function output($data, $format)
    {
        switch($format){
            case 'json':
            default:
                $response = new JsonResponse();
                $response->setData(array('data' => $data, 'status' => 200, 'message' => null));
                return $response;
                break;
        }
    }
    
    public function getSiteId($token, $site = null)
    {
        if ( $site === null )
        {
            return $this->get('access_token')->setToken($token)->getClientId();
        }
    }
    
}