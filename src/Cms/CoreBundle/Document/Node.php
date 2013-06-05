<?php

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Node
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="nodes")
 */
class Node
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Hash
     */
    private $metadata;

    /**
     * @MongoDB\Hash
     */
    private $view;

    /**
     * @MongoDB\Hash
     */
    private $comments;

    /**
     * Constructor. Sets created to current unix timestamp int
     */
    public function __construct()
    {
        $this->created = time();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Updates the modified timestamp
     *
     * @param int $timestamp
     * @returns void
     */
    public function updateModifiedTimestamp($timestamp = null)
    {
        if ( ! isset($timestamp) )
        {
            $timestamp = time();
        }
        $this->addMetadata(array('modified' => $timestamp));
    }

    /**
     * void. Use Add instead
     */
    public function setMetadata()
    {
        throw new \Exception('Pleas use the addMetadata method instead of the setMetadata method');
    }

    /**
     * Add metadata
     *
     * @param array $newMetadata
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addMetadata(array $newMetadata)
    {
        $metadataArray = $this->getMetadata();
        if ( ! $metadataArray )
        {
            $metadataArray = array();
        }
        
        if ( isset($newMetadata['modified']) )
        {
            $metadataArray['modified'] = $newMetadata['modified'];
        }
        if ( isset($newMetadata['created']) )
        {
            $metadataArray['created'] = $newMetadata['created'];
        }
        if ( isset($newMetadata['slug']) )
        {
            $metadataArray['slug'] = $newMetadata['slug'];
        }
        if ( isset($newMetadata['title']) )
        {
            $metadataArray['title'] = $newMetadata['title'];
        }
        if ( isset($newMetadata['template']) )
        {
            $metadataArray['template'] = $newMetadata['template'];
        }
        if ( isset($newMetadata['site']) AND is_array($newMetadata['site']) )
        {
            if ( ! isset($newMetadata['site']['id']) OR ! isset($newMetadata['site']['domain']) )
            {
                throw new \InvalidArgumentException('Metadata Site Value Object must contain at least an id and a domain');
            }
            $metadataArray['site'] = $newMetadata['site'];
        }
        if ( isset($newMetadata['tags']) AND is_array($newMetadata['tags']) )
        {
            $tagsArray = $this->getMetadata('tags');
            if ( ! is_array($tagsArray) OR empty($tagsArray) )
            {
                $tagsArray = array();
            }
            $metadataArray['tags'] = array_merge($tagsArray, $newMetadata['tags']);
        }
        if ( isset($newMetadata['categories']) AND is_array($newMetadata['categories']))
        {
            $categoriesArray = $this->getMetadata('categories');
            if ( ! is_array($categoriesArray) or empty($categoriesArray) )
            {
                $categoriesArray = array();
            }
            array_push($categoriesArray, $newMetadata['categories']);
            $metadataArray['categories'] = $categoriesArray;
        }
        if ( isset($newMetadata['author']) AND is_array($newMetadata['author']) )
        {
            $metadataArray['author'] = $newMetadata['author'];
        }
        $this->metadata = $metadataArray;
        return $this;
    }

    /**
     * Data param is an associate array with the following structure: array('metaType' => 'someMetaType', 'pattern' => 'somePatternToMatch')
     * The pattern element is optional. If not passed on an array property, clears entire property
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function removeMetadata(array $data)
    {
        if ( isset($data['metaType']) )
        {
            switch($data['metaType']){
                case 'tags':
                    if ( isset($this->metadata['tags']) )
                    {
                        if ( isset($data['pattern']) )
                        {
                            $this->metadata['tags'] = array_values( array_diff( $this->metadata['tags'], array($data['pattern']) ) );
                        }
                        else
                        {
                            $this->metadata['tags'] = array();
                        }
                    }
                    break;
                case 'categories':
                    if ( isset($this->metadata['categories']) AND is_array($this->metadata['categories']) )
                    {
                        if ( isset($data['pattern']) )
                        {
                            if ( count($data['pattern']) === 1 AND isset($data['pattern']['parent']) )
                            {
                                // find all category array key which contain the specified parent
                                foreach ($this->metadata['categories'] as $metaCatKey => $metaCatValue) {
                                    if ( $metaCatValue['parent'] === $data['pattern']['parent'] )
                                    {
                                        $keys[] = $metaCatKey;
                                    }
                                }
                            }
                            else
                            {
                                // find all keys with a specific parent/sub pair
                                $keys = array_keys($this->metadata['categories'], $data['pattern']);
                            }
                            foreach ($keys as $key) {
                                unset($this->metadata['categories'][$key]);
                            }
                        }
                        else
                        {
                            $this->metadata['categories'] = array();
                        }
                    }
                    break;
                default:
                    unset($this->metadata[$data['metaType']]);
                    
            }
        }
        else
        {
            throw new \InvalidArgumentException('removeMetadata requires one argument as an associative array with a metaType index and an optional pattern index');
        }
        
    }

    /**
     * Get metadata
     *
     * @param null $metaType
     * @return mixed $metadata
     */
    public function getMetadata($metaType = null)
    {
        if ( isset($metaType) )
        {
            switch($metaType){
                case 'modified':
                    if ( isset($this->metadata['modified']) )
                    {
                        return $this->metadata['modified'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'created':
                    if ( isset($this->metadata['tags']) )
                    {
                        return $this->metadata['created'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'slug':
                    if ( isset($this->metadata['slug']) )
                    {
                        return $this->metadata['slug'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'title':
                    if ( isset($this->metadata['title']) )
                    {
                        return $this->metadata['title'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'template':
                    if ( isset($this->metadata['template']) )
                    {
                        return $this->metadata['template'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'site':
                    if ( isset($this->metadata['site']) )
                    {
                        return $this->metadata['site'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'tags':
                    if ( isset($this->metadata['tags']) )
                    {
                        return $this->metadata['tags'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'categories':
                    if ( isset($this->metadata['categories']) )
                    {
                        return $this->metadata['categories'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                case 'author':
                    if ( isset($this->metadata['author']) )
                    {
                        return $this->metadata['author'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                default:
                    return null;
            }
        }
        else
        {
            return $this->metadata;
        }
    }

    public function setView(array $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Get view
     *
     * @return hash $view
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set comments
     *
     * @param $comments
     * @return $this
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return hash $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

}
