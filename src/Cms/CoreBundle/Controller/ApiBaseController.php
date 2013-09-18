<?php
/**
 * User: Brian Anderson
 * Date: 9/17/13
 * Time: 10:38 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\Api\ApiException;
use Symfony\Component\Stopwatch\Stopwatch;

class ApiBaseController extends Controller {

    public function getResourcesArray($resources, $format, $fields)
    {
        $resourcesArray = array();
        foreach ($resources as $resource) {
            $resourcesArray[] = $this->get('api_node_adopter')->setResource($resource)->setFormat($format)->convert($fields);
        }
        return $resourcesArray;
    }

    public function getDefaultVars($_format)
    {
        $vars = array();
        $vars['accessToken'] = $this->getAccessToken($_format);
        $vars['fields'] = $this->getFields();
        $vars['clientId'] = $this->get('access_token')->setToken($vars['accessToken'])->getClientId();
        $vars['options'] = array(
            'limit' => (int)$this->getRequest()->query->get('limit', 10),
            'offset' => (int)$this->getRequest()->query->get('offset', 0),
            'sortBy' => $this->getRequest()->query->get('sortBy', 'created'),
            'sortOrder' => $this->getRequest()->query->get('sortOrder', 'desc'),
        );
        $vars['params'] = array(
            'search' => $this->getRequest()->query->get('search'),
            'title' => $this->getRequest()->query->get('title'),
            'domain' => $this->getRequest()->query->get('domain'),
            'locale' => $this->getRequest()->query->get('locale'),
            'category' => $this->getRequest()->query->get('category'),
            'categorySub' => $this->getRequest()->query->get('category_sub'),
            'tags' => $this->getRequest()->query->get('tags') ? explode(',', $this->getRequest()->query->get('tags')) : null,
            'slug' => $this->getRequest()->query->get('slug'),
            'createdAfter' => $this->getRequest()->query->get('created_after'),
            'createdBefore' => $this->getRequest()->query->get('created_before'),
            'contentTypeName' => $this->getRequest()->query->get('content_type_name'),
            'authorFirstName' => $this->getRequest()->query->get('author_first_name'),
            'authorLastName' => $this->getRequest()->query->get('author_last_name'),
        );
        $vars['stopwatch'] = new Stopwatch();
        $vars['stopwatch']->start('loadTime');
        return $vars;
    }

    public function getFields()
    {
        $fields = $this->getRequest()->query->get('fields');
        if ( $fields ){
            $fields = explode(',', $fields);
        }else{
            $fields = array();
        }
        return $fields;
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

    public function createCollectionMeta($options, $count, $format, $loadTimeEvent)
    {
        return array(
            'loadTime' => $loadTimeEvent->getDuration().' ms',
            'status' => 200,
            'limit' => $options['limit'],
            'offset' => $options['offset'],
            'count' => $count,
            '_links' => $this->get('api_base')->setFormat($format)->getCollectionLinks($options, $count),
        );
    }


}