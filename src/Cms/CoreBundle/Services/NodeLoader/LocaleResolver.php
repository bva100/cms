<?php
/**
 * User: Brian Anderson
 * Date: 6/10/13
 * Time: 12:41 PM
 */

namespace Cms\CoreBundle\Services\NodeLoader;

class LocaleResolver {

    /**
     * @var array $nodes of \Cms\CoreBundle\Document\Node
     */
    private $nodes;

    /**
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    private $request;

    /**
     * @param array $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
        return $this;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
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
     * Turns a collection of nodes into one node entity by resolving locale
     *
     * @param null $localeParam
     * @return mixed
     * @throws \Exception
     */
    public function resolve($localeParam = null)
    {
        if ( ! isset($this->nodes) OR ! isset($this->request) )
        {
            throw new \Exception('Both nodes and request must be set prior to calling the NodeLoader LocaleResolver');
        }
        if ( count($this->nodes) === 1 )
        {
            return $this->nodes->getSingleResult();
        }
        if ( ! isset($localeParam) )
        {
            $localeParam = $this->request->getLocale();
        }
        foreach ($this->nodes as $node) {
            if ( $node->getLocale() === $localeParam )
            {
                return $node;
            }
        }
    }

}