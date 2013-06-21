<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NodeRepository
 */
class NodeRepository extends DocumentRepository {

    /**
     * Find one node by host and slug
     *
     * @param string $host
     * @param string $slug
     */
    public function findOneByHostAndSlug($host, $slug)
    {
        return $this->createQueryBuilder()
            ->field('host')->equals($host)
            ->field('slug')->equals($slug)
            ->getQuery()->execute()->getSingleResult();
    }

    /**
     * Find one node by host and slug and locale
     *
     * @param string $host
     * @param string $slug
     * @param string $locale
     */
    public function fineOneByHostAndSlugAndLocale($host, $slug, $locale)
    {
        return $this->createQueryBuilder()
            ->field('host')->equals($host)
            ->field('slug')->equals($slug)
            ->field('locale')->equals($locale)
            ->getQuery()->execute()->getSingleResult();
    }

    /**
     * Find by host and locale and contentTypeName and Taxonomy. Can paginate if needed.
     *
     * @param string $host
     * @param string $locale
     * @param string $contentTypeName
     * @param array $category
     * @param array $tags
     * @param array $params
     */
    public function findByHostAndLocaleAndContentTypeNameAndTaxonomy($host, $locale, $contentTypeName, array $category, array $tags, array $params)
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
            ->field('host')->equals($host)
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
    public function findBySiteIdAndContentType($siteId, $contentTypeName, array $params = array('offset' => 0, 'limit' => 20))
    {
        $qb = $this->createQueryBuilder();
        if ( isset($siteId) )
        {
            $qb->field('siteId')->equals($siteId);
        }
        if ( isset($contentType) )
        {
            $qb->field('contentTypeName')->equals($contentTypeName);
        }
        return $qb->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    /**
     * @param string $siteId
     * @param array $params
     */
    public function findBySiteId($siteId, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->findBySiteIdAndContentType($siteId, null, $params);
    }

    /**
     * @param string $contentTypeName
     * @param array $params
     */
    public function findByContentTypeName($contentTypeName, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->findBySiteIdAndContentType(null, $contentTypeName, $params);
    }

    
}