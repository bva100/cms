<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 11:25 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\Api\ApiException;

class ApiController extends Controller {

    public function nodeReadV1Action($ids, $_format)
    {
        $resources = array();
        $accessToken = $this->getAccessToken($_format);
        $clientId = $this->get('access_token')->setToken($accessToken)->getClientId();
        $idsArray = explode(',', $ids);
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndIds($clientId, $idsArray);
        foreach ($nodes as $node) {
            $resources[] = $this->get('api_node_adopter')->setResource($node)->convert();
        }
        return $this->get('api_output')
            ->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node', 'plural' => 'nodes'))
            ->output();
    }

    public function getAccessToken($format)
    {
        $accessToken = $this->getRequest()->headers->get('Authorization');
        if ( ! $accessToken )
        {
            throw new ApiException(10002, $format);
        }
        return $accessToken;
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

    public function tokenAction()
    {
        // resets secret and offers token for a client id
        $clientId = $this->getRequest()->query->get('clientId');
        $secret = $this->get('access_token')->createSecret();
        
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        if ( ! $site ){
            throw $this->createNotFoundException('site with id '.$clientId.' not found');
        }
        $site->setClientSecret($secret);
        $success = $this->get('persister')->save($site);
        if ( ! $success ){
            throw new \Exception('not able to update client secret');
        }
        $token = $this->get('access_token')->createToken($clientId, $secret);
        return new Response('the new token is <br> '.$token);
    }

    public function testAction()
    {
        require 'PipeStack.php';
        $accessToken = 'PzKmOxi72jxlNd3icrQJWhbMJ62BIWhl7iHA5LIS1wPu3yLJ5Gp08hDR6oZKL_wtqsTix-FFgS-2gw2wgbw5fmaIcxjklITn1BNjYLXEXLe67cRTVeA4VcRKgHjw24z1';
        $PipeStack = new \PipeStack($accessToken, 'local');
        $results = $PipeStack->get('nodes/51d8234b18a5166d3e000001,5213b15218a516290f000001');
//        $results = $PipeStack->get('node/find', array('slug' => 'review/cloud-front', 'domain' => 'localhost'));
//        $results = $PipeStack->get('node/search', array('category' => 'travel', 'domain' => 'localhost'));

//        echo '<pre>', \var_dump($results); die();



        //single
//        return new Response('<h1>'.$results->node->title.'</h1><p>'.$results->node->view->html.'</p>');

        //many
        echo 'response: '.$results->meta->code.'<br>';
        foreach ($results->nodes as $node) {
            echo '<h2>', $node->title, '</h1>', '<p>', $node->view->html, '</p><br />';
        }
        die('<br> end');

    }

    
}