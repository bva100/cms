<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NodeRepository
 */
class NodeRepository extends DocumentRepository {

    /**
     * Find node by domain and slug
     *
     * @param string $domain
     * @param string $slug
     */
    public function findByDomainAndSlug($domain, $slug)
    {
        return $this->createQueryBuilder()
            ->field('domain')->equals($domain)
            ->field('slug')->equals($slug)
            ->getQuery()->execute();
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

    // get all nodes with content type id

}