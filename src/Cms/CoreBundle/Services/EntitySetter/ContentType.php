<?php


namespace Cms\CoreBundle\Services\EntitySetter;

use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\ContentType as Entity;

class ContentType extends AbstractEntitySetter {

    /**
     * @param Base $entity
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setEntity(Base $entity)
    {
        if ( ! $entity instanceof Entity ){
            throw new \InvalidArgumentException('entity passed to ContentType Entity Setter class must be ContentType');
        }
        $this->entity = $entity;
        return $this;
    }

    public function patch()
    {
        extract($this->request->request->all());
        if ( isset($name) ){
            $this->entity->setName($name);
        }
        if ( isset($description) ){
            $this->entity->setDescription($description);
        }
        if ( isset($formatType) and $formatType === 'static' )
        {
            $this->entity->addFormat('static');
        }
        else if( isset($formatType) and $formatType === 'dynamic')
        {
            $this->entity->addFormat('single');
            $this->entity->addFormat('loop');
        }
        if ( isset($loops) ){
            $this->entity->setLoops($loops);
        }
        if ( isset($templateName) ){
            $this->entity->setTemplateName($templateName);
        }
        if ( isset($slugPrefix) ){
            $this->entity->setSlugPrefix($slugPrefix);
        }
        if ( isset($categories) ){
            $this->entity->setCategories($categories);
        }
        if ( isset($tags) ){
            $this->entity->setTags($tags);
        }
        if ( isset($fields) ){
            $this->entity->setFields($fields);
        }
        return $this->entity;
    }

}