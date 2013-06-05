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
     * @return void
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
        if ( isset($newMetadata['slug']) AND is_string($newMetadata['slug']) )
        {
            $metadataArray['slug'] = $newMetadata['slug'];
        }
        if ( isset($newMetadata['title']) AND is_string($newMetadata['title']) )
        {
            $metadataArray['title'] = $newMetadata['title'];
        }
        if ( isset($newMetadata['template']) AND is_string($newMetadata['template']) )
        {
            $metadataArray['template'] = $newMetadata['template'];
        }
        if ( isset($newMetadata['site']) AND is_array($newMetadata['site']) )
        {
            if ( ! isset($newMetadata['site']['id']) OR ! isset($newMetadata['site']['domain']) )
            {
                throw new \InvalidArgumentException('Metadata Site Value Object must contain at least an id and a domain');
            }
            if ( is_string($newMetadata['site']['id']) AND is_string($newMetadata['site']['domain']) )
            {
                $metadataArray['site'] = $newMetadata['site'];
            }
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
     * remove all metadata
     */
    public function removeAllMetadata()
    {
        $this->metadata = array();
        return $this;
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
            return isset($this->metadata[$metaType]) ? $this->metadata[$metaType] : null;
        }
        else
        {
            return $this->metadata;
        }
    }

    /**
     * Add view data to a specific locale
     * Pattern: $data = array('contentType' => 'value')
     *
     * @param array $data
     * @return $this
     */
    public function addView(array $data)
    {
        $viewArray = $this->getView();
        if ( ! $viewArray )
        {
            $viewArray = array();
        }
        if ( isset($data['content']) AND is_string($data['content']) )
        {
            $viewArray['content'] = $data['content'];
        }
        if ( isset($data['json']) AND is_string($data['json']) )
        {
            $viewArray['json'] = $data['json'];
        }
        if ( isset($data['xml']) AND is_string($data['xml']) )
        {
            $viewArray['xml'] = $data['xml'];
        }
        $this->view = $viewArray;
        return $this;
    }

    /**
     * Remove view data associated with a specific view contentType
     *
     * @param null $contentType
     */
    public function removeView($contentType = null)
    {
        if ( isset($contentType) )
        {
            unset($this->view[$contentType]);
        }
    }

    /**
     * Remove all view data in this node
     */
    public function removeAllViews()
    {
        $this->view = array();
    }

    /**
     * Get view data array. Optionally pass a
     *
     * @param null $contentType
     * @return null
     */
    public function getView($contentType = null)
    {
        if ( ! isset($contentType) )
        {
            return $this->view;
        }
        else
        {
            return isset($this->view[$contentType]) ? $this->view[$contentType] : null;
        }
    }


}
