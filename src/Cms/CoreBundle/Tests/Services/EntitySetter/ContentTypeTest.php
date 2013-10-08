<?php

use \Symfony\Component\HttpFoundation\Request;
use \Cms\CoreBundle\Document\ContentType as Entity;
use \Cms\CoreBundle\Services\EntitySetter\ContentType as Service;

class ContentTypeTest extends \PHPUnit_Framework_TestCase {

    public function testPatch()
    {
        $name = 'foobar';
        $description = 'fooz and barz';
        $formatType = 'dynamic';
        $loops = array('bing', 'bong');
        $templateName = 'steveJobs';
        $slugPrefix = 'foo/';
        $categories = array(array('parent' => 'foo'), array('parent' => 'foo', 'sub' => 'barz'));
        $tags = array('foobar', 'foo', 'bar');
        $fields = array('fooy' => 'gooey');

        $request = new Request(array(), get_defined_vars());
        $entity = new Entity();
        $service = new Service;
        $service->setRequest($request);
        $service->setEntity($entity);

        $patchedEntity = $service->patch();
        $this->assertEquals($name, $patchedEntity->getName());
        $this->assertEquals($description, $patchedEntity->getDescription());
        $this->assertEquals(array('single', 'loop'), $patchedEntity->getFormats());
        $this->assertEquals($loops, $patchedEntity->getLoops());
        $this->assertEquals($templateName, $patchedEntity->getTemplateName());
        $this->assertEquals($slugPrefix, $patchedEntity->getSlugPrefix());
        $this->assertEquals($categories, $patchedEntity->getCategories());
        $this->assertEquals($tags, $patchedEntity->getTags());
        $this->assertEquals($fields, $patchedEntity->getFields());
    }

}