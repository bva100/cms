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
    public function findDynamicNodesByContentTypeAndDomain($typeName, $domain, $locale = null, array $params)
    {
        $qb = $this->createQueryBuilder()
            ->field('metadata.site.domain')->equals($domain)
            ->field('metadata.type.name')->equals($typeName)
            ->field('metadata.type.format')->equals('single');
        if ( isset($locale) )
        {
            $qb->field('metadata.locale')->equals($locale);
        }
        if ( isset($params['categories']) AND is_array($params['categories']) AND ! empty($params['categories']) )
        {
            if ( isset($params['categories']['parent']) )
            {
                $qb->field('metadata.categories.parent')->equals($params['categories']['parent']);
            }
            if ( isset($params['categories']['sub']) )
            {
                $qb->field('metadata.categories.sub')->equals($params['categories']['sub']);
            }
        }
        if ( isset($params['tags']) AND is_array($params['tags']) AND ! empty($params['tags']) )
        {
            $qb->field('metadata.tags')->in($params['tags']);
        }
        return $qb->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

}