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
use Cms\CoreBundle\Services\Api\EntityAdopters;

class ApiBaseController extends Controller {

    public function notFoundAction($_format)
    {
        throw new ApiException(10004, $_format);
    }

    public function getResourcesArray($adopter, $resources, $format, array $fields = array())
    {
        $resourcesArray = array();
        foreach ($resources as $resource) {
            $resourcesArray[] = $adopter->setResource($resource)->setFormat($format)->convert($fields);
        }
        return $resourcesArray;
    }

    public function getDefaultVars($_format)
    {
        $request = $this->getRequest();
        $query = $request->query;
        $vars = array();
        $vars['accessToken'] = $this->getAccessToken($_format);
        $vars['fields'] = $this->getFields();
        $vars['clientId'] = $this->get('access_token')->setToken($vars['accessToken'])->getClientId();
        $vars['options'] = array(
            'limit' => (int)$query->get('limit', 10),
            'offset' => (int)$query->get('offset', 0),
            'sortBy' => $query->get('sortBy', 'created'),
            'sortOrder' => $query->get('sortOrder', 'desc'),
        );
        $vars['params'] = array(
            'search' => $query->get('search'),
            'title' => $query->get('title'),
            'domain' => $query->get('domain'),
            'locale' => $query->get('locale'),
            'category' => $query->get('category'),
            'categorySub' => $query->get('category_sub'),
            'tags' => $query->get('tags') ? explode(',', $query->get('tags')) : null,
            'slug' => $query->get('slug'),
            'createdAfter' => $query->get('created_after'),
            'createdBefore' => $query->get('created_before'),
            'contentTypeName' => $query->get('content_type_name'),
            'authorFirstName' => $query->get('author_first_name'),
            'authorLastName' => $query->get('author_last_name'),
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

    public function decodeObjectParams($objectParams, $format)
    {
        switch($format){
            case 'json':
            default:
                if ( ! is_string($objectParams) ){
                    throw new ApiException(10006, $format, 'the "objectParams" expects a json_encode string. '.ucfirst(gettype($objectParams)).' was passed.');
                }
                
                return json_decode($objectParams, TRUE);
                break;
        }
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