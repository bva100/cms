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
        $siteId = $this->getSiteId($this->getRequest()->query->get('access_token'));
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' not found');
        }
        $this->checkNodeAndSiteId($node, $siteId);
        return $this->output($node->getVars(), $this->getRequest()->query->get('format'));
    }

    public function nodeFindV1Action()
    {
        $siteId = $this->getSiteId($this->getRequest()->query->get('access_token'));
        $domain = $this->getRequest()->query->get('domain');
        $slug = $this->getRequest()->query->get('slug');
        $locale = $this->getRequest()->query->get('locale');
        $repo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $node = $locale ? $repo->findOneByDomainAndSlug($domain, $slug) :  $repo->findOneByDomainAndLocaleAndSlug($domain, $locale, $slug);
        $this->checkNodeAndSiteId($node, $siteId);
        return $this->output($node->getVars(), $this->getRequest()->query->get('format'));
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

    public function checkNodeAndSiteId($node, $siteId)
    {
        if ( $node->getSiteId() !== $siteId )
        {
            throw new \Exception('invalid access token');
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