<?php

namespace Cms\CoreBundle\Services\SiteManager;

use Cms\PersisterBundle\Services\Persister;


class unique {

    private $persister;

    /**
     * @param $persister
     * @return $this
     */
    public function setPersister(Persister $persister)
    {
        $this->persister = $persister;
        return $this;
    }

    /**
     * @return Persister
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     * Is namespace unique?
     *
     * @param $namespace
     * @return bool
     */
    public function namespaceCheck($namespace)
    {
        $results = $this->persister->getRepo('CmsCoreBundle:Site')->findOneByNamespace($namespace);
        return $results ? false : true;
    }

    /**
     * Is domain unique?
     *
     * @param $domain
     * @return bool
     */
    public function domainCheck($domain)
    {
        $results = $this->persister->getRepo('CmsCoreBundle:Site')->findOneByDomain($domain);
        return $results ? false : true;
    }

}