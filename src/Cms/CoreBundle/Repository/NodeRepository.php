<?php

namespace Cms\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NodeRepository
 */
class NodeRepository extends DocumentRepository {

    /**
     * Find nodes by ID and, in the process, verify site id
     *
     * @param $siteId
     * @param $ids
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function findBySiteIdAndIds($siteId, array $ids)
    {
        return $this->createQueryBuilder()
            ->field('siteId')->equals($siteId)
            ->field('id')->in($ids)
            ->getQuery()->execute();
    }

    public function findBySiteId($siteId, array $params = array(), array $options = array(), $count = false)
    {
        extract($this->getDefaultOptions($options));
        $qb = $this->createQueryBuilder()
            ->field('siteId')->equals($siteId);
        $qb = $this->addParametersToQuery($qb, $params);
        if ( $count ){
            return $qb->getQuery()->execute()->count();
        }
        return $qb->sort($sortBy, $sortOrder)->skip($offset)->limit($limit)->getQuery()->execute();
    }

    public function getDefaultOptions(array $options)
    {
        if ( ! isset($options['limit']) ){
            $options['limit'] = 10;
        }
        if ( ! isset($options['offset']) ){
            $options['offset'] = 0;
        }
        if ( ! isset($options['sortBy']) ){
            $options['sortBy'] = 'created';
        }
        if ( ! isset($options['sortOrder']) ){
            $options['sortOrder'] = 'desc';
        }
        return $options;
    }

    public function addParametersToQuery($qb, array $params)
    {
        extract($params);
        if ( isset($domain) ){
            $qb->field('domain')->equals($domain);
        }
        if ( isset($locale) ){
            $qb->field('locale')->equals($locale);
        }
        if ( isset($category) ){
            $qb->field('categories.parent')->equals((string)$category);
        }
        if ( isset($categorySub) ){
            $qb->field('categories.sub')->equals($categorySub);
        }
        if ( isset($tags) ){
            $qb->field('tags')->in($tags);
        }
        if ( isset($slug) ){
            $qb->field('slug')->equals($slug);
        }
        if ( isset($createdAfter) ){
            $qb->field('created')->gte((int)$createdAfter);
        }
        if ( isset($createdBefore) ){
            $qb->field('created')->lte((int)$createdBefore);
        }
        if ( isset($contentTypeName) ){
            $qb->field('contentTypeName')->equals($contentTypeName);
        }
        if ( isset($authorFirstName) ){
            $qb->field('author.name.first')->equals(ucfirst($authorFirstName));
        }
        if ( isset($authorLastName) ){
            $qb->field('author.name.last')->equals(ucfirst($authorLastName));
        }
        if ( isset($title) ){
            $qb->field('title')->equals($title);
        }
        if ( isset($state) ){
            $qb->field('state')->equals($state);
        }
        
        if ( isset($search) ){
            $qb->addOr($qb->expr()->field('title')->equals(new \MongoRegex('/.*'.$search.'.*/i')));
            $qb->addOr($qb->expr()->field('view.html')->equals(new \MongoRegex('/.*'.$search.'.*/i')));
        }
        return $qb;
    }
    
    

    /**
     * Find one node by domain and slug
     *
     * @param $domain
     * @param $slug
     * @return array|mixed|null
     */
    public function findOneByDomainAndSlug($domain, $slug)
    {
        return $this->createQueryBuilder()
            ->field('domain')->equals($domain)
            ->field('slug')->equals($slug)
            ->getQuery()->execute()->getSingleResult();
    }

    /**
     * Find one by domain and locale and locale
     *
     * @param $domain
     * @param $locale
     * @param $slug
     * @return array|mixed|null
     */
    public function findOneByDomainAndLocaleAndSlug($domain, $locale, $slug)
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
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     * @return mixed
     */
    public function findByDomainAndLocaleAndContentTypeNameAndTaxonomy($domain, $locale, $contentTypeName, array $category, array $tags, array $params = array('offset' => 0, 'limit' => 20, 'sort' => array('by' => 'created', 'order' => 'desc')))
    {
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
            if ( isset($category['sub']) AND ! empty($category['sub']) )
            {
                $qb->field('categories.sub')->equals($category['sub']);
            }
        }
        if ( ! empty($tags) )
        {
            $qb->field('tags')->in($tags);
        }
        if ( isset($params['search']) )
        {
            $qb->addOr($qb->expr()->field('title')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
            $qb->addOr($qb->expr()->field('view.html')->equals(new \MongoRegex('/.*'.$params['search'].'.*/i')));
        }
        if ( isset($params['siteId']) )
        {
            $qb->field('siteId')->equals($params['siteId']);
        }
        return $qb->sort($params['sort']['by'], $params['sort']['order'])->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    /**
     * @param $siteId
     * @param $contentTypeName
     * @param $state
     * @param array $params
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function findBySiteIdAndContentTypeAndState($siteId, $contentTypeName, $state, array $params = array('offset' => 0, 'limit' => 20, 'sort' => array('by' => 'created', 'order' => 'desc')))
    {
        $qb = $this->createQueryBuilder()->field('siteId')->equals($siteId);
        if ( isset($contentTypeName) )
        {
            $qb->field('contentTypeName')->equals($contentTypeName);
        }
        if ( isset($state) )
        {
            $qb->field('state')->equals($state);
        }
        if ( isset($params['format']) )
        {
            $qb->field('format')->equals($params['format']);
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
        return $qb->sort($params['sort']['by'], $params['sort']['order'])->skip($params['offset'])->limit($params['limit'])->getQuery()->execute();
    }

    /**
     * @param $siteId
     * @param $contentTypeName
     * @param array $params
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     */
    public function findBySiteIdAndContentType($siteId, $contentTypeName, array $params = array('offset' => 0, 'limit' => 20))
    {
        return $this->findBySiteIdAndContentTypeAndState($siteId, $contentTypeName, 'active', $params);
    }

    /**
     * @param $siteId
     * @param $contentTypeName
     * @param $format
     * @return array|mixed|null
     */
    public function findOneBySiteIdAndContentTypeNameAndFormat($siteId, $contentTypeName, $format)
    {
        return $this->createQueryBuilder()
            ->field('siteId')->equals($siteId)
            ->field('contentTypeName')->equals($contentTypeName)
            ->field('format')->equals($format)
            ->getQuery()->execute()->getSingleResult();
    }
    
}