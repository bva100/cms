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
use Cms\CoreBundle\Services\Api\ApiException;

class ApiController extends Controller {

    public function nodeReadV1Action($id, $_format)
    {
        $_format = $this->getRequest()->headers->get('Accept', 'application/json');
        $accessToken = $this->getRequest()->headers->get('Authorization');
        $clientId = $this->get('access_token')->setToken($accessToken)->getClientId();
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node ){
            throw new ApiException(404, $_format);
        }
        $this->checkNodeAndSiteId($node, $clientId);

        return $this->output($_format, array('node' => $node->getVars()));
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
        $search = $this->getRequest()->query->get('q');
        $categoryParent = $this->getRequest()->query->get('category');
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

    public function loopFindV1Action()
    {
        $siteId = $this->getSiteId($this->getRequest()->query->get('access_token'));
        $domain = $this->getRequest()->query->get('domain');
        $locale = $this->getRequest()->query->get('locale');
        $slug = $this->getRequest()->query->get('slug');
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
        $offset = $limit*($page-1);
        $nodeRepo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $node = $nodeRepo->findOneByDomainAndLocaleAndSlug($domain, $locale, $slug);
        if ( ! $node )
        {
            throw $this->createNotFoundException('A loop node does not exist for the given parameters');
        }
        $this->checkNodeAndSiteId($node, $siteId);
        $data = new \stdClass();
        $data->node = $node->getVars();
        $loopRaw = $this->get('loop_loader')->setNode($node)->setNodeRepo($nodeRepo)->setParams(\get_defined_vars())->load();
        $loop = array();
        foreach ($loopRaw as $loopNode) {
            $loop[] = $loopNode->getVars();
        }
        $data->loop = $loop;
        return $this->output($data, $this->getRequest()->query->get('format'));
    }

    public function output($format, array $data, array $meta = array('code' => 200), array $notifications = array())
    {
        switch($format){
            case 'json':
            default:
                $response = new JsonResponse();
                $response->setData(array($data, 'meta' => $meta, 'notifications' => $notifications));
                return $response;
                break;
        }
    }

    public function checkNodeAndSiteId($node, $siteId)
    {
        if ( ! $node )
        {
            throw new ApiException(404, $this->getRequest()->query->get('format'));
        }
        if ( $node->getSiteId() !== $siteId )
        {
            throw new ApiException(401, $this->getRequest()->query->get('format'));
        }
    }

    public function testAction()
    {
        require 'PipeStack.php';
        $accessToken = 'NTFjMDAzM2QxOGE1MTYyYzA0MDAwMDAyOmMzMjkwYzc1N2ZkMGRhOTBkYmI2ODFkMzZiMTcxYjky';
        $PipeStack = new \PipeStack($accessToken, 'local');
        $results = $PipeStack->get('nodes/51d8234b18a5166d3e000000');
//        $results = $PipeStack->get('node/find', array('slug' => 'review/cloud-front', 'domain' => 'localhost'));
//        $results = $PipeStack->get('node/search', array('category' => 'travel', 'domain' => 'localhost'));
        echo '<pre>', \var_dump($results); die();
    }
    
}