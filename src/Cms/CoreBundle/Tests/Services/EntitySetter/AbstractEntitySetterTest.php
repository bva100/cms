<?php

use \Cms\CoreBundle\Services\EntitySetter\AbstractEntitySetter;
use \Symfony\Component\HttpFoundation\Request;
use \Cms\CoreBundle\Document\Base;

class AbstractEntitySetterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Concrete
     */
    private $service;

    public function setUp()
    {
        $this->service = new Concrete();
    }

    public function testRequestSetGet()
    {
        $request = new Request();
        $returnObj = $this->service->setRequest($request);
        $this->assertEquals($this->service, $returnObj);
        $this->assertEquals($request, $this->service->getRequest());
    }

    public function testEntitySetGet()
    {
        $entity = new ConcreteBase();
        $returnObj = $this->service->setEntity($entity);
        $this->assertEquals($this->service, $returnObj);
        $this->assertEquals($entity, $this->service->getEntity());
    }

}

class Concrete extends AbstractEntitySetter {

    public function setEntity(Base $entity){
        $this->entity = $entity;
        return $this;
    }

    public function patch(){}
}

class ConcreteBase extends Base {}