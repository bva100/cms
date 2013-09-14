<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 5:03 PM
 */

namespace Cms\CoreBundle\Services\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException {

    public function __construct($code, $format)
    {
        $baseUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'api.pipestack.com';
        $data = array();
        $data['code'] = $code;
        $data['moreInfo'] = $baseUrl.'/docs/exception/'.$code;
        
        switch ($code) {
            case 400:
                $data['type'] = 'Param Error';
                $data['message'] = 'A required parameter is missing or is malformed. Be sure to add the api version parameter (v), an acess token if needed (access_token), and any other required parameters for this resource.';
                ksort($data);
                parent::__construct(400, $this->createMessage($data, $format));
                break;
            case 401:
                $data['type'] = 'Invalid Auth';
                $data['message'] = 'Please ensure you have passed a valid access token parameter.';
                ksort($data);
                parent::__construct(401, $this->createMessage($data, $format));
                break;
            case 403:
                $data['type'] = 'Forbidden';
                $data['message'] = 'Authentication was successful, however the client does not have access to the information requested. Although this error can occur for many reasons, it often occurs when the client has exceeded his or her rate limit for this hour or if the client\'s last payment was invalid';
                ksort($data);
                parent::__construct(403, $this->createMessage($data, $format));
                break;
            case 404:
                $data['type'] = 'Resource Does Not Exit';
                $data['message'] = 'Resource not found.';
                ksort($data);
                parent::__construct(404, $this->createMessage($data, $format));
                break;
            case 405:
                $data['type'] = 'Method Not Allowed';
                $data['message'] = 'Attempting to use the POST method when resource only accepts the GET method, or vice versa. Can also be applied to PUT and DELETE. Be sure to use an appropriate method for this resource.';
                ksort($data);
                parent::__construct(405, $this->createMessage($data, $format));
                break;
            case 500:
            default:
                $data['type'] = 'Internal Server Error';
                $data['message'] = 'A server error has occurred. Please try again soon.';
                ksort($data);
                parent::__construct(500, $this->createMessage($data, $format));
                break;
        }
    }

    public function createMessage($data, $format)
    {
        switch ($format) {
            case 'html':
                return '<h1>'.$data['code'].' '.$data['type'].'</h1><p>'.$data['message'].'</p><p><a href="'.$data['moreInfo'].'" target="_blank"/>'.$data['moreInfo'].'</a></p>';
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

}