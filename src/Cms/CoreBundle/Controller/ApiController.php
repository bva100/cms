<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 11:25 AM
 */

namespace Cms\CoreBundle\Controller;

use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
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

    public function nodeSearchV1Action()
    {
        $siteId = $this->getSiteId($this->getRequest()->query->get('access_token'));
        $domain = $this->getRequest()->query->get('domain');
        $locale = $this->getRequest()->query->get('locale');
        $contentTypeName = $this->getRequest()->query->get('content_type_name');
        $categoryParent = $this->getRequest()->query->get('category');
        $search = $this->getRequest()->query->get('search');
        $categorySub = $this->getRequest()->query->get('category_child');
        $tags = $this->getRequest()->query->get('tags');
        $tags = $tags ? explode(',', $tags ) : array();
        $sortBy = $this->getRequest()->query->get('sortBy');
        if ( ! $sortBy )
        {
            $sortBy = 'created';
        }
        $sortOrder = $this->getRequest()->query->get('sortOrder');
        if ( ! $sortOrder )
        {
            $sortOrder = 'desc';
        }
        $limit = (int)$this->getRequest()->query->get('limit');
        if ( ! $limit )
        {
            $limit = 12;
        }
        $page = $this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findByDomainAndLocaleAndContentTypeNameAndTaxonomy($domain, $locale, $contentTypeName, array('parent' => $categoryParent, 'sub' => $categorySub), $tags, array(
            'sort' => array('by' => $sortBy, 'order' => $sortOrder),
            'search' => $search,
            'limit' => $limit,
            'offset' => $limit*($page-1),
            'siteId' => $siteId,
        ));
        $nodesArray = array();
        foreach ($nodes as $node) {
            $nodesArray[] = $node->getVars();
        }
        return $this->output($nodesArray, $this->getRequest()->query->get('format'));
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