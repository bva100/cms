<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * mediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class mediaRepository extends DocumentRepository {

    public function findAllBySiteId($siteId, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->createQueryBuilder()
            ->field('siteId')->equals($siteId)
            ->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    public function findAllBySiteIdAndType($siteId, $type, array $params = array('offset' => 0, 'limit' => 20, array('sort' =>array('by' => 'created', 'order' => 'desc'))))
    {
        $qb = $this->createQueryBuilder()
            ->field('siteId')->equals($siteId);
        if ( isset($type) )
        {
            $qb->field('mime')->equals(new \MongoRegex($type.'.*/'));
        }
        if ( isset($params['startDate']) )
        {
            $qb->field('created')->gte((int)$params['startDate']);
        }
        if ( isset($params['endDate']) )
        {
            $qb->field('created')->lte((int)$params['endDate']);
        }
        if ( isset($params['association']) )
        {
            if ( $params['association'] === 'unattached' )
            {
                $qb->addOr($qb->expr()->field('nodeIds')->equals(null));
                $qb->addOr($qb->expr()->field('nodeIds')->size(0));
            }
        }
        if ( isset($params['search']) )
        {
            $qb->addOr($qb->expr()->field('metadata.title')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
            $qb->addOr($qb->expr()->field('metadata.description')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
        }
        return $qb->sort($params['sort']['by'], $params['sort']['order'])->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

}