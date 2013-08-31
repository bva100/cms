<?php

class PipeStack {

    const USER_AGENT = 'PipeStack PHP SDK apiVersion=';

    private $accessToken;

    private $baseUrl;

    private $version;

    public function __construct($accessToken, $env = 'prod', $version = '1')
    {
        $this->setBaseUrl($env);
        $this->setAccessToken($accessToken);
        $this->setVersion($version);
    }

    public function setAccessToken($accessToken)
    {
        if ( ! is_string($accessToken) )
        {
            throw new \InvalidArgumentException('Access token is expected to be a string, type '.gettype($accessToken).' was passed');
        }
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setBaseUrl($env)
    {
        switch($env){
            case 'dev':
                $this->baseUrl = 'dev.pipestack.com/api/';
                break;
            case 'prod':
            default:
                $this->baseUrl =  'api.pipestack.com/';
                break;
        }
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = 'v'.$version;
        return $this;
    }

    public function getApiUrl()
    {
        return $this->baseUrl.$this->version.'/';
    }

    public function getBearer()
    {
        return 'Authorization: Bearer '.$this->accessToken;
    }

    public function getUserAgent()
    {
        return self::USER_AGENT.$this->version;
    }

    public function formatHeader($format)
    {
        switch($format){
            default:
            case 'json':
            case 'JSON':
                return 'Content-Type: application/json';
                break;
        }
    }

    public function setDefaultParams($params)
    {
        if ( ! isset($params['format']) )
        {
            $params['format'] = 'json';
        }
        if ( ! isset($params['schema']) )
        {
            $params['schema'] = 'http://';
        }
        return $params;
    }

    public function createResponse($data, $status)
    {
        $response = new stdClass;
        $response->data = json_decode($data);
        $response->status = $status;
        return $response;
    }
    
    public function get($endpoint, array $params = array('format' => 'json', 'schema' => 'http://'))
    {
        $params = $this->setDefaultParams($params);
        $params['access_token'] = $this->accessToken;
        $queryStr = http_build_query($params);
        $headers = array($this->formatHeader($params['format']), $this->getBearer());
        $uri = $params['schema'].$this->getApiUrl().$endpoint.'?'.$queryStr;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $uri);
        $data = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $this->createResponse($data, $status);
    }

    public function testinit()
    {
        $headers = array('Content-Type: application/json');
        if ($app->access_token())
        {
            $headers[] = 'Authorization: Bearer '.$app->access_token();
        }

        // create uri
        $uri = URL::base(TRUE).'api/users.json?user_id='.$user_id.'&access_token='.urlencode($app->access_token()).'&v=.8';

        //cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'AuthMyApp PHP SDK api_version=.8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $uri);
    }

}