<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NodeRepository
 */
class NodeRepository extends DocumentRepository {

    /**
     * Find one node by domain and slug
     *
     * @param string $domain
     * @param string $slug
     */
    public function findOneByDomainAndSlug($domain, $slug)
    {
        return $this->createQueryBuilder()
            ->field('domain')->equals($domain)
            ->field('slug')->equals($slug)
            ->getQuery()->execute()->getSingleResult();
    }

    /**
     * Find one node by domain and slug and locale
     *
     * @param string $domain
     * @param string $slug
     * @param string $locale
     */
    public function fineOneByDomainAndSlugAndLocale($domain, $slug, $locale)
    {
        return $this->createQueryBuilder()
            ->field('domain')->equals($domain)
            ->field('slug')->equals($slug)
            ->field('locale')->equals($locale)
            ->getQuery()->execute()->getSingleResult();
    }

    /**
     * Find by domain and locale and contentTypeName and Taxonomy. Can paginate if needed.
     *
     * @param string $domain
     * @param string $locale
     * @param string $contentTypeName
     * @param array $category
     * @param array $tags
     * @param array $params
     */
    public function findByDomainAndLocaleAndContentTypeNameAndTaxonomy($domain, $locale, $contentTypeName, array $category, array $tags, array $params)
    {
        if ( ! isset($params['offset']) )
        {
            $params['offset'] = 0;
        }
        if ( ! isset($params['limit']) )
        {
            $params['limit'] = 20;
        }
        $qb = $this->createQueryBuilder()
            ->field('domain')->equals($domain)
            ->field('locale')->equals($locale)
            ->field('contentTypeName')->equals($contentTypeName)
            ->field('format')->equals('single');
        if ( ! empty($category) )
        {
            if ( isset($category['parent']) )
            {
                $qb->field('categories.parent')->equals($category['parent']);
            }
            if ( isset($category['sub']) )
            {
                $qb->field('categories.sub')->equals($category['sub']);
            }
        }
        if ( ! empty($tags) )
        {
            $qb->field('tags')->in($tags);
        }
        return $qb->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    /**
     * @param string $siteId
     * @param string $contentTypeName
     * @param array $params
     * @returns collection of entities
     */
    public function findBySiteIdAndContentTypeAndState($siteId, $contentTypeName, $state, array $params = array('offset' => 0, 'limit' => 20))
    {
        $qb = $this->createQueryBuilder()
                ->field('siteId')->equals($siteId)
                ->field('contentTypeName')->equals($contentTypeName);
        if ( isset($state) )
        {
            $qb->field('state')->equals($state);
        }
        if ( isset($params['startDate']) )
        {
            $qb->field('created')->gte((int)$params['startDate']);
        }
        if ( isset($params['endDate']) )
        {
            $qb->field('created')->lte((int)$params['endDate']);
        }
        if ( isset($params['tags']) AND ! empty($params['tags']) )
        {
            $qb->field('tags')->in($params['tags']);
        }
        if ( isset($params['categoryParent']) )
        {
            $qb->field('categories.parent')->equals((string)$params['categoryParent']);
        }
        if ( isset($params['categorySub']) )
        {
            $qb->field('categories.sub')->equals((string)$params['categorySub']);
        }
        if ( isset($params['authorFirstName']) )
        {
            $qb->field('author.name.first')->equals(ucfirst($params['authorFirstName']));
        }
        if ( isset($params['authorLastName']) )
        {
            $qb->field('author.name.last')->equals(ucfirst($params['authorLastName']));
        }
        if ( isset($params['search']) )
        {
            $qb->addOr($qb->expr()->field('title')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
            $qb->addOr($qb->expr()->field('view.html')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
        }
        
        return $qb->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    /**
     * @param string $siteId
     * @param string $contentTypeName
     * @param array $params
     * @returns collection of entities
     */
    public function findBySiteIdAndContentType($siteId, $contentTypeName, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->findBySiteIdAndContentTypeAndState($siteId, $contentTypeName, 'active', $params);
    }

    /**
     * @param string $siteId
     * @param array $params
     */
    public function findBySiteId($siteId, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->findBySiteIdAndContentType($siteId, null, $params);
    }
    
}