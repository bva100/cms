<?php
/**
 * User: Brian Anderson
 * Date: 6/10/13
 * Time: 11:56 PM
 */

namespace Cms\CoreBundle\Services\ParamManager;

class Core {

    /**
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    private $request;

    /**
     * @var string $locale
     */
    private $locale;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return $this
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $path
     * @return string
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get parameters as array
     *
     * @return array
     */
    public function get()
    {
        $params = \explode('/', $this->path);
        $paramsArray =  array(
            'slug' => $this->path,
            'domain' => $this->request->getHost(),
            'locale' => $this->getLocale(),
        );
        if ( count($params) > 1 )
        {
            unset($params[0]);
            $paramsArray['taxonomyParent'] = array_shift($params);
            $paramsArray['taxonomySub'] = $params;
        }

        \parse_str($this->request->getQueryString());
        if ( isset($locale) AND is_string($locale) )
        {
            $paramsArray['locale'] = $locale;
        }
        if ( isset($offset) )
        {
            $paramsArray['offset'] = (int)$offset;
        }
        if ( isset($limit) )
        {
            $paramsArray['limit'] = (int)$limit;
        }
        return $paramsArray;
    }

}