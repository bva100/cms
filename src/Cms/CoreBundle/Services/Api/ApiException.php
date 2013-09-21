<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 5:03 PM
 */

namespace Cms\CoreBundle\Services\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;
use RuntimeException;

class ApiException extends HttpException {

    public function __construct($code, $format, $customMessage = null)
    {
        $baseUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'http://api.pipestack.com';
        $data = array();
        $data['errorCode'] = $code;
        $data['moreInfo'] = $baseUrl.'/docs/errors/'.$code;

        switch($code){
            case 10001:
                $data['message'] = 'The access token passed does not have access to the site\'s resources. This often occurs when the site\'s client secret is altered, but an old access token is used. Please update the access token and try again.';
                $data['status'] = 403;
                break;
            case 10002:
                $data['message'] = 'An access token was not passed in the header, yet this resource requires an access token.';
                $data['status'] = 401;
                break;
            case 10003:
                $data['message'] = 'There was a problem deleting at least one resource, though some resources may have been deleted.';
                $data['status'] = 404;
                break;
            case 10004:
                $data['message'] = 'The endpoint requested was not found. Please be ensure you have passed the correct URL.';
                $data['status'] = 404;
                break;
            case 20001:
                $data['message'] = 'A node with the given parameters does not exist. Please ensure the parameters are correct. If you have passed many IDs this error could be thrown if one was not found. This error can occur if an incorrect access token was passed.';
                $data['status'] = 404;
                break;
            case 20002:
                $data['message'] = 'A node with the given ID (or IDs) does not exist. Please ensure the passed ID is correct';
                $data['status'] = 404;
                break;
            case 20003:
                $data['message'] = 'Node failed to validate when attempting to save. Please check all required parameters';
                $data['status'] = 400;
                break;
            case 20004:
                $data['message'] = 'The server encountered an error when deleting a node resource. If the problem persists, please follow the link under "moreInfo" and report the issue.';
                $data['status'] = 500;
                break;
            default:
                throw new RuntimeException('Code '.$code.' not found in code registry');
                break;
        }
        if ( isset($customMessage) )
        {
            $data['message'] = $customMessage;
        }
        
        switch ($data['status']) {
            case 400:
                $data['description'] = 'Parameter error. A required parameter is missing or is malformed.';
                ksort($data);
                parent::__construct(400, $this->createMessage($data, $format), null, $this->createHeaders($format));
                break;
            case 401:
                $data['description'] = 'Invalid auth. Please ensure you have passed a valid access token parameter.';
                ksort($data);
                parent::__construct(401, $this->createMessage($data, $format), null, $this->createHeaders($format));
                break;
            case 403:
                $data['description'] = 'Forbidden. Authentication was successful, however the client does not have access to the information requested. Although this error can occur for many reasons, it often occurs when the client has exceeded his or her rate limit for this hour or if the client\'s last payment was invalid';
                ksort($data);
                parent::__construct(403, $this->createMessage($data, $format), null, $this->createHeaders($format));
                break;
            case 404:
                $data['description'] = 'Resource not found.';
                ksort($data);
                parent::__construct(404, $this->createMessage($data, $format), null, $this->createHeaders($data, $format));
                break;
            case 405:
                $data['description'] = 'Method not allowed. Attempting to use the POST method when resource only accepts the GET method, or vice versa. Can also be applied to PUT, PATCH and DELETE. Be sure to use an appropriate method for this resource.';
                ksort($data);
                parent::__construct(405, $this->createMessage($data, $format), null, $this->createHeaders($format));
                break;
            case 500:
            default:
                $data['description'] = 'Internal server error. A server error has occurred. Please try again soon.';
                ksort($data);
                parent::__construct(500, $this->createMessage($data, $format), null, $this->createHeaders($format));
                break;
        }
    }

    public function createMessage($data, $format)
    {
        switch ($format) {
            case 'html':
                return '<h1>Error with Status '.$data['status'].' and Code '.$data['errorCode'].'</h1><p>'.$data['description'].'</p><p>'.$data['message'].'</p><p><a href="'.$data['moreInfo'].'" target="_blank"/>'.$data['moreInfo'].'</a></p>';
                break;
            case 'xml':
                $data = array_flip($data);
                $xml = new \SimpleXMLElement('<root/>');
                array_walk_recursive($data, array ($xml, 'addChild'));
                return $xml->asXML();
                break;
            case 'json':
            default:
                return json_encode(array('meta' => $data));
            break;
        }
    }

    public function createHeaders($format)
    {
        switch($format){
            case 'html':
                return array('Content-Type' => 'text/html');
                break;
            case 'xml':
                return array('Content-Type' => 'application/xml');
                break;
            case 'json':
            default:
                return array('Content-Type' => 'application/json');
                break;
        }
    }

}