<?php
/**
 * User: Brian Anderson
 * Date: 7/28/13
 * Time: 9:47 AM
 */

use Cms\CoreBundle\Services\TemplateLoader\Client;

class ClientTest extends PHPUnit_Framework_TestCase {

    private $client;

    private $codeBlock = "{% extends 'Core:Base:HTML' %}{% use 'DogBlog:Master:CustomBlock' %}{% block menuPrimary %}<ul>\n    <li><a href=\'/review/greenies\'>Greenies</a></li>\n    <li><a href=\'/review/woobie\'>Woobie</a></li>\n  </ul>\n{% endblock %}\n\n{% block menuFooter %}\n  <ul>\n    <li>Dog Blog, INC</li>\n    <li>Location: Cambridge, MA</li>\n  </ul>\n{% endblock %}";

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->client = new Client();
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::setCode
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getNonWhiteSpaceCode
     */
    public function testGetNonWhiteSpaceCode()
    {
        $this->client->setCode($this->codeBlock);
        $nonWhiteSpace = $this->client->getNonWhiteSpaceCode();
        if ( strpos($nonWhiteSpace, " ") !== false )
        {
            $test = false;
        }else{
            $test = true;
        }
        $this->assertTrue($test);
        $whiteSpace = $nonWhiteSpace." ";
        if ( strpos($whiteSpace, " ") !== false )
        {
            $test = false;
        }else{
            $test = true;
        }
        $this->assertFalse($test);
        $whiteSpace = " ".$nonWhiteSpace;
        if ( strpos($whiteSpace, " ") !== false )
        {
            $test = false;
        }else{
            $test = true;
        }
        $this->assertFalse($test);
        $this->client->setCode($this->codeBlock);
        $nonWhiteSpace = $this->client->getNonWhiteSpaceCode();
        if ( strpos($nonWhiteSpace, " ") !== false )
        {
            $test = false;
        }else{
            $test = true;
        }
        $this->assertTrue($test);
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getExtends
     */
    public function testGetExtends()
    {
        $this->client->setCode($this->codeBlock);
        $this->assertEquals('Core:Base:HTML', $this->client->getExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getExtends
     */
    public function testGetExtendsEmpty()
    {
        $this->client->setCode('{% block primaryNav %}{{ parent() }}{% endblock %}');
        $this->assertEmpty($this->client->getExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getUses
     */
    public function testGetUses()
    {
        $this->client->setCode('{% use "foobar" %}{% use "foo-boom" %}'.$this->codeBlock);
        $this->assertCount(3, $this->client->getUses());
        $this->assertEquals(array('foobar', 'foo-boom', 'DogBlog:Master:CustomBlock'), $this->client->getUses());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getUses
     */
    public function testGetEmptyUses()
    {
        $this->client->setCode('{% block primaryNav %}{{ parent() }}{% endblock %}');
        $this->assertEmpty($this->client->getUses());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getComponents
     */
    public function testGetComponents()
    {
        $this->client->setCode("{% use 'DogBlog:Foo-Bar:Baz' %}".$this->codeBlock);
        $components = $this->client->getComponents();
        $this->assertEquals(array(
            'extends' => 'Core:Base:HTML',
            'uses' => array('DogBlog:Foo-Bar:Baz', 'DogBlog:Master:CustomBlock'),
            'rawCode' => "{% block menuPrimary %}<ul>\n    <li><a href=\'/review/greenies\'>Greenies</a></li>\n    <li><a href=\'/review/woobie\'>Woobie</a></li>\n  </ul>\n{% endblock %}\n\n{% block menuFooter %}\n  <ul>\n    <li>Dog Blog, INC</li>\n    <li>Location: Cambridge, MA</li>\n  </ul>\n{% endblock %}",
        ), $components);
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Client::getComponents
     */
    public function testGetComponentsEmpty()
    {
        $code = '{% block primaryNav %}{{ parent() }}{% endblock %}';
        $this->client->setCode($code);
        $components = $this->client->getComponents();
        $this->assertEmpty($components['extends']);
        $this->assertEmpty($components['uses']);
        $this->assertEquals($code, $components['rawCode']);
    }
    
}
