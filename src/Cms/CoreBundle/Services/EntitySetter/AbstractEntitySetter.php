<?php


namespace Cms\CoreBundle\Services\EntitySetter;

use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Base;

abstract class AbstractEntitySetter {

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Base
     */
    protected $entity;

    /**
     * Create setter with concrete class. Must be instance of a concrete base document object.
     *
     * @param $entity
     * @return $this
     */
    abstract public function setEntity(Base $entity);

    /**
     * @return Base
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Return the updated object. Patch ensures that null properties are not set.
     *
     * @return Base
     */
    abstract public function patch();

}