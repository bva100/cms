<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 9:38 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

/**
 * Class TwigEnvironment
 * @package Cms\CoreBundle\Services\TemplateLoader
 */
class TwigEnvironment {

    /**
     * @var implements Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var
     */
    private $cacheDir;

    /**
     * @param $kernal
     */
    public function __construct($kernal)
    {
        $this->cacheDir = $kernal->getRootDir().'/cache/client/twig';
    }

    /**
     * @param  $cacheDir
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return void
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @param \Cms\CoreBundle\Services\TemplateLoader\implements $loader
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return \Cms\CoreBundle\Services\TemplateLoader\implements
     */
    public function getLoader()
    {
        return $this->loader;
    }

    public function load()
    {
        $twig =  new \Twig_Environment($this->loader, array('cache' => $this->cacheDir, 'auto_reload' => true));
        $truncateFunction = new \Twig_SimpleFilter('truncate', function($str, $length){
            return substr($str, 0, $length);
        }, array('is_safe'=> array('html')));
        $twig->addFilter($truncateFunction);
        $pageFunction = new \Twig_SimpleFunction('page', function($type){
            $params = array();
            parse_str($_SERVER['QUERY_STRING'], $params);
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            if ( $type === 'next' )
            {
                $page = $page + 1;
            }
            if ( $type === 'prev' AND $page > 1)
            {
                $page = $page -1;
            }
            $queryStr = '?page='.$page;
            if ( isset($params['limit']) )
            {
                $queryStr .= '&limit='.$params['limit'];
            }
            if ( isset($params['sortBy']) )
            {
                $queryStr .= '&sortBy='.$params['sortBy'];
            }
            if ( isset($params['sortOrder']) )
            {
                $queryStr .= '&sortOrder='.$params['sortOrder'];
            }
            if ( isset($params['search']) )
            {
                $queryStr .= '&search='.$params['search'];
            }
            if ($pos_get = strpos($_SERVER["REQUEST_URI"], '?')){
                $uri = substr($_SERVER["REQUEST_URI"], 0, $pos_get);
            }else{
                $uri = $_SERVER["REQUEST_URI"];
            }
            return $uri.$queryStr;
        });
        $twig->addFunction($pageFunction);
        $assetFunction = new \Twig_SimpleFunction('asset', function ($name, $type, $cacheBustVersion = 1, $subdomain = 'one') {
            if ( $_SERVER["HTTP_HOST"] === 'localhost' ){
                $domain =  'static-localhost';
            }else{
                $domain =  'static-pipestack.com';
            }
            if ( preg_match('/[^a-z_\-0-9]/i', str_replace(':', '', $name)) )
            {
                throw new \InvalidArgumentException('Invalid asset name. Asset name must be alphanumeric.');
            }
            if ( ! in_array($type, array('css', 'js')) )
            {
                throw new \InvalidArgumentException('Invalid asset type '.$type.'. Must be "css" or "js"');
            }
            if ( ! is_int($cacheBustVersion) )
            {
                throw new \InvalidArgumentException('Asset Cache Bust Version parameter expects an Integer, '.gettype($cacheBustVersion).' was passed');
            }
            if( ! preg_match('/^[a-zA-Z]+$/', $subdomain) ){
                throw new \InvalidArgumentException('Asset subdomain can only contain letters');
            }
            $url = 'http://'.$subdomain.'.'.$domain.'/'.$name.'_'.$cacheBustVersion.'.'.$type;
            switch($type){
                case 'css':
                    return '<link href="'.$url.'" rel="stylesheet">';
                    break;
                case 'js':
                    return '<script type="text/javascript" src="'.$url.'"></script>';
                    break;
                default:
                    return '';
                    break;
            }
        }, array('is_safe' => array('html')));
        $twig->addFunction($assetFunction);
        return $twig;
    }

}