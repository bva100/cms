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
            throw new \InvalidArgumentException('Access token is expected to be a string, type '.gettype($accessToken).' was provided');
        }
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setBaseUrl($env)
    {
        switch($env){
            case 'local':
                $this->baseUrl = 'localhost/app_dev.php/api/';
                break;
            case 'dev':
                $this->baseUrl = 'dev.pipestack.com/api/';
                break;
            case 'staging':
                $this->baseUrl = 'staging.pipestack.com/api/';
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
        return 'Authorization: '.$this->accessToken;
    }

    public function getUserAgent()
    {
        return self::USER_AGENT.$this->version;
    }

    public function getAcceptHeader($format)
    {
        switch($format){
            default:
            case 'json':
                return 'Accept: application/json';
                break;
        }
    }

    public function setDefaultOptions($options)
    {
        if ( ! isset($options['format']) )
        {
            $options['format'] = 'json';
        }
        if ( ! isset($options['protocol']) )
        {
            $options['protocol'] = 'http://';
        }
        return $options;
    }

    public function createResponse($data, $format, $status)
    {
        switch($format){
            case 'json':
            default:
                return json_decode($data);
                break;
        }
    }

    public function getEndpointUri($endpoint,$params, $options)
    {
        $queryStr = '';
        if ( ! empty($params) ){
            $queryStr = http_build_query($params);
        }
        $uri = $options['protocol'].$this->getApiUrl().$endpoint;
        if ( $queryStr ){
            $uri .= '?'.$queryStr;
        }
        return $uri;
    }
    
    public function get($endpoint, array $params = array(), array $options = array())
    {
        $options = $this->setDefaultOptions($options);
        $uri = $this->getEndpointUri($endpoint, $params, $options);
        $headers = array($this->getBearer(), $this->getAcceptHeader($options['format']));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $uri);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    public function create($endpoint, array $objectParams = array(), array $options = array())
    {
        $options = $this->setDefaultOptions($options);
        $uri = $this->getEndpointUri($endpoint, $objectParams, $options);
        $headers = array($this->getBearer(), $this->getAcceptHeader($options['format']));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('objectParams' => json_encode($objectParams))));
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    public function delete($endpoint)
    {
        $options = $this->setDefaultOptions(array());
        $uri = $this->getEndpointUri($endpoint, $params, $options);
    }


}