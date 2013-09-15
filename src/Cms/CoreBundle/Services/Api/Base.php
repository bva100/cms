<?php
/**
 * User: Brian Anderson
 * Date: 9/14/13
 * Time: 6:16 PM
 */

namespace Cms\CoreBundle\Services\Api;

class Base {

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $queryString;

    public function __construct()
    {
        if ( isset($_SERVER['HTTPS']) ){
            $this->protocol = $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://' ;
        }else{
            $this->protocol = 'http://';
        }
        if ( isset($_SERVER['HTTP_HOST']) ){
            $this->host = $_SERVER['HTTP_HOST'];
        }else{
            $this->host = 'localhost';
        }
        if ( isset($_SERVER['DOCUMENT_URI']) ){
            $this->uri = $_SERVER['DOCUMENT_URI'];
        }else{
            $this->uri = '/';
        }
        if ( isset($_SERVER['QUERY_STRING']) ){
            $this->queryString = $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : '';
        }else{
            $this->queryString = '';
        }
        $this->format = 'json';
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get the API base URL for the given request
     *
     * @return string
     */
    public function getBaseApiUrl()
    {
        if ( ! isset($_SERVER['HTTPS']) OR ! isset($_SERVER['HTTP_HOST']) OR ! isset($_SERVER['REQUEST_URI']) )
        {
            return '';
        }
        $baseUrl = '';
        $vPos = strpos($this->uri, '/v');
        if ( $vPos ){
            $baseUrl = substr($this->uri, 0, $vPos+3);
        }
        return $this->protocol.$this->host.$baseUrl;
    }

    /**
     * Get the API current requests' url
     *
     * @param bool $includeQueryStr
     * @return string
     */
    public function getCurrentUrl($includeQueryStr = true)
    {
        $url =  $this->protocol.$this->host.$this->uri;
        if ( $this->queryString AND $includeQueryStr )
        {
            $url .= '?'.$this->queryString;
        }
        return $url;
    }

    /**
     * Get the url associated with the next page of a collection
     *
     * @param array $options
     * @return string
     */
    public function getNextUrl(array $options)
    {
        parse_str($this->queryString);
        $offset = $options['limit']+$options['offset'];
        unset($options);
        $queryStr = http_build_query(get_defined_vars());
        return $this->getCurrentUrl(false).'?'.$queryStr;
    }

    /**
     * Get the url associated with previous page of a collection
     *
     * @param array $options
     * @return string
     */
    public function getPreviousUrl(array $options)
    {
        parse_str($this->queryString);
        $offset = $options['offset']-$options['limit'];
        unset($options);
        $queryStr = http_build_query(get_defined_vars());
        return $this->getCurrentUrl(false).'?'.$queryStr;
    }

    /**
     * Get the url associated with the first page of a collection
     *
     * @return string
     */
    public function getFirstUrl()
    {
        parse_str($this->queryString);
        $offset = 0;
        $queryStr = http_build_query(get_defined_vars());
        return $this->getCurrentUrl(false).'?'.$queryStr;
    }

    public function getLastUrl(array $options, $count)
    {
        parse_str($this->queryString);
        $offset = floor($count/$options['limit'])*$options['limit'];
        unset($options);
        unset($count);
        $queryStr = http_build_query(get_defined_vars());
        return $this->getCurrentUrl(false).'?'.$queryStr;
    }

    /**
     * Get the array for meta _links associated with a collection ( HAL compliant ).
     * 
     * @param array $options
     * @param int $count
     * @return array
     */
    public function getCollectionLinks(array $options, $count)
    {
        $links = array();
        $links['self'] = array('href' => $this->getCurrentUrl());
        $current =  $options['limit'] + $options['offset'];
        if ( $current < $count )
        {
            $links['next'] = array('href' => $this->getNextUrl($options));
        }
        if ( $options['offset'] > 0 )
        {
            $links['previous'] = array('href' => $this->getPreviousUrl($options));
        }
        if ( $count > $options['limit'] AND $options['offset'] > 0 )
        {
            $links['first'] = array('href' => $this->getFirstUrl());
        }
        if ( $count > $options['limit'] AND $current < $count )
        {
            $links['last'] = array('href' => $this->getLastUrl($options, $count));
        }
        return $links;
    }

}