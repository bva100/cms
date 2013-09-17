<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 12:34 AM
 */

namespace Cms\CoreBundle\Services\TemplateLoader;

/**
 * Class MongoTwigLoader
 * @package Cms\CoreBundle\Services\TemplateLoader
 */
class MongoTwigLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface {

    /**
     * @var a repo created with a mongoDB entity
     */
    protected $repo;

    /**
     * @param \Doctrine\ODM\MongoDB\DocumentManager $em
     * @param $class
     */
    public function __construct(\Doctrine\ODM\MongoDB\DocumentManager $em, $class)
    {
        $this->repo = $em->getRepository($class);
    }

    /**
     * Get source (content) given a template name
     */
    public function getSource($name)
    {
        return $this->getValue($name, 'content');
    }

    /**
     * Does template with this name exist?
     */
    public function exists($name)
    {
        return (bool)$results = $this->repo->findOneBy( array('name' => $name) );

    }

    /**
     * Get the base string for the cache key. Note: this gets hashed in system cache file dir
     */
    public function getCacheKey($name)
    {
        return 'TemplateKey'.$name;
    }

    /**
     * Checks if template has updated
     */
    public function isFresh($name, $time = null)
    {
        if ( ! isset($time) )
        {
            $time = time();
        }
        if (false === $lastModified = $this->getValue($name, 'last_modified'))
        {
            return false;
        }
        return $lastModified <= $time;
    }

    /**
     * Query Mongo DB to get a value
     */
    protected function getValue($name, $field)
    {
        $results = $this->repo->findOneBy( array('name' => $name) );
        if ( $results )
        {
            switch($field){
                case 'content':
                    return $results->getContent();
                    break;
                case 'last_modified':
//                    return $results->getLast_modified():
                    return false;
                default:
                    true;
            }
        }
        return null;
    }


}