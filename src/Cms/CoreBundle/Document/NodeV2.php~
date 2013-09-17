<?php
/**
 * User: Brian Anderson
 * Date: 6/7/13
 * Time: 1:49 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Node
 * @package Cms\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class NodeV2 {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $state;

    /**
     * @MongoDB\Date
     */
    private $updated;

    /**
     * @MongoDB\Hash
     */
    private $metadata;

    /**
     * @MongoDB\Hash
     */
    private $view;

    public function __construct()
    {
        $this->setState('active');
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
     * Set state
     *
     * @param $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return date $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Depreciated
     *
     * @throws \Exception
     */
    public function setMetadata()
    {
        throw new \Exception('Please use the addMetadata method instead of the setMetadata method');
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
            $metadataArray['slug'] =$newMetadata['slug'];
        }
        if ( isset($newMetadata['title']) AND is_string($newMetadata['title']) )
        {
            $metadataArray['title'] = $newMetadata['title'];
        }
        if ( isset($newMetadata['template']) AND is_string($newMetadata['template']) )
        {
            $metadataArray['template'] = $newMetadata['template'];
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
        if ( isset($newMetadata['type']) and is_array($newMetadata['type']) )
        {
            $metadataArray['type'] = $newMetadata['type'];
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
     * Set view
     *
     * @param hash $view
     * @return self
     */
    public function setView($view)
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
}
