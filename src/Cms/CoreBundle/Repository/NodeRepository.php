<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NodeRepository
 *
 */
class NodeRepository extends DocumentRepository {

    /**
     * Find a list of nodes by searching via domain type and locale
     */
    public function findDynamicNodesByContentTypeAndDomain($typeName, $domain, $locale = null)
    {
        if ( isset($locale) )
        {
            return $this->createQueryBuilder()
                ->field('metadata.site.domain')->equals($domain)
                ->field('metadata.type.name')->equals($typeName)
                ->field('metadata.type.format')->equals('single')
                ->field('metadata.locale')->equals($locale)
                ->getQuery()
                ->execute();
        }
        else
        {
            return $this->createQueryBuilder()
                ->field('metadata.site.domain')->equals($domain)
                ->field('metadata.type.name')->equals($typeName)
                ->field('metadata.type.format')->equals('single')
                ->getQuery()
                ->execute();
        }
    }

}