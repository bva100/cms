<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * SiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteRepository extends DocumentRepository {

    public function findOneByNamespace($namespace)
    {
        return $this->createQueryBuilder()
            ->field('namespace')->equals($namespace)
            ->getQuery()->execute()->getSingleResult();
    }

    public function findOneByDomain($domain)
    {
        return $this->createQueryBuilder()
            ->field('domains')->equals($domain)
            ->getQuery()->execute()->getSingleResult();
    }

    public function findBySiteIdsAndState(array $siteIds, $state)
    {
        return $this->createQueryBuilder()
            ->field('id')->in($siteIds)
            ->field('state')->equals($state)
            ->getQuery()->execute();
    }

}