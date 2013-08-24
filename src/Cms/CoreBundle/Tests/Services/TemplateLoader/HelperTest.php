<?php
/**
 * User: Brian Anderson
 * Date: 7/28/13
 * Time: 5:55 AM
 */

use Cms\CoreBundle\Services\TemplateLoader\Helper;

class HelperTest extends PHPUnit_Framework_TestCase {

    private $helper;

    private $rawCodeBlock = '{% block menuPrimary %}
  <ul>
    <li><a href="/review/greenies">Greenies</a></li>
    <li><a href="/review/woobie">Woobie</a></li>
  </ul>
{% endblock %}

{% block menuFooter %}
  <ul>
    <li>Dog Blog, INC</li>
    <li>Location: Cambridge, MA</li>
  </ul>
{% endblock %}';

    private $codeBlock = "{% extends 'Core:Base:HTML' %}\n {% use 'DogBlog:Master:CustomBlock' %} \n {% block menuPrimary %}\n  <ul>\n    <li><a href=\'/review/greenies\'>Greenies</a></li>\n    <li><a href=\'/review/woobie\'>Woobie</a></li>\n  </ul>\n{% endblock %}\n\n{% block menuFooter %}\n  <ul>\n    <li>Dog Blog, INC</li>\n    <li>Location: Cambridge, MA</li>\n  </ul>\n{% endblock %}";

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->helper = new Helper();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->helper = new Helper();
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::setRawCode
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::getRawCode
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::setRawCode
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::getNonWhiteSpaceCode
     */
    public function testGetNonWhiteSpaceCode()
    {
        $this->helper->setRawCode($this->rawCodeBlock);
        $nonWhiteSpace = $this->helper->getNonWhiteSpaceCode();
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
        $this->helper->setRawCode($this->codeBlock);
        $nonWhiteSpace = $this->helper->getNonWhiteSpaceCode();
        if ( strpos($nonWhiteSpace, " ") !== false )
        {
            $test = false;
        }else{
            $test = true;
        }
        $this->assertTrue($test);
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsExtends
     */
    public function testDoesNotContainExtends()
    {
        $this->helper->setRawCode("{% block foobar %}{% endblock %}");
        $this->assertFalse($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsExtends
     */
    public function testDoesNotContainExtendsReal()
    {
        $this->helper->setRawCode($this->rawCodeBlock);
        $this->assertFalse($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsExtends
     */
    public function testDoesContainExtends()
    {
        $this->helper->setRawCode("{% extends 'Core:Base:HTML' %}{% block foobar %}{% endblock %}");
        $this->assertTrue($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsExtends
     */
    public function testDoesContainExtendsReal()
    {
        $this->helper->setRawCode($this->codeBlock);
        $this->assertTrue($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsUses
     */
    public function testDoesNotContainUses()
    {
        $this->helper->setRawCode("{% block foobar %}{% endblock %}");
        $this->assertFalse($this->helper->containsUses());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsUses
     */
    public function testDoesNotContainUsesReal()
    {
        $this->helper->setRawCode($this->rawCodeBlock);
        $this->assertFalse($this->helper->containsUses());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsUses
     */
    public function testDoesContainUses()
    {
        $this->helper->setRawCode("{% use 'Core:Base:HTML' %}{% block foobar %}{% endblock %}");
        $this->assertTrue($this->helper->containsUses());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsUses
     */
    public function testDoesContainUsesReal()
    {
        $this->helper->setRawCode($this->codeBlock);
        $this->assertTrue($this->helper->containsUses());
    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validateTwigSyntax
//     */
//    public function testValidateTwigSyntax()
//    {
//        $this->helper->setRawCode($this->rawCodeBlock);
//        $syntaxArray = $this->helper->validateTwigSyntax();
//        $this->assertTrue($syntaxArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validateTwigSyntax
//     */
//    public function testInvalidTwigSyntax()
//    {
//        $this->helper->setRawCode("{% block foobar }{% endblock %}");
//        $syntaxArray = $this->helper->validateTwigSyntax();
//        $this->assertFalse($syntaxArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validate
//     */
//    public function testValid()
//    {
//        $this->helper->setRawCode($this->rawCodeBlock);
//        $validArray = $this->helper->validate();
//        $this->assertTrue($validArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validate
//     */
//    public function testInvalid()
//    {
//        $this->helper->setRawCode($this->codeBlock);
//        $validArray = $this->helper->validate();
//        $this->assertFalse($validArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validate
//     */
//    public function testInvalidIncludeExtends()
//    {
//        $this->helper->setRawCode("{% extends 'Core:Base:HTML' %}{% block foobar %}{% endblock %}");
//        $validArray = $this->helper->validate();
//        $this->assertFalse($validArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validate
//     */
//    public function testInvalidIncludeUses()
//    {
//        $this->helper->setRawCode("{% use 'Core:Base:HTML' %}{% block foobar %}{% endblock %}");
//        $validArray = $this->helper->validate();
//        $this->assertFalse($validArray['status']);
//    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validate
//     */
//    public function testInvalidIncludeReal()
//    {
//        $this->helper->setRawCode($this->codeBlock);
//        $validArray = $this->helper->validate();
//        $this->assertFalse($validArray['status']);
//    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::validIncludeName
     */
    public function testValidIncludeName()
    {
        $this->assertTrue($this->helper->validIncludeName('Core:Base:HTML'));
        $this->assertTrue($this->helper->validIncludeName('Core:Base:HTML-custom'));
        $this->assertFalse($this->helper->validIncludeName('{% extends Core:Base:HTML %}'));
        $this->assertFalse($this->helper->validIncludeName('{% use Core:Base:HTML-Custom'));
    }

//    /**
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::createCod
//     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::setRawCode
//     */
//    public function testCreateCode()
//    {
//        $this->helper->setRawCode($this->rawCodeBlock);
//        $result = $this->helper->createCode('Core:Base:HTML', array("NEW-NEW-NEW", "SomethingElse"));
//        $this->assertContains("{% extends 'Core:Base:HTML' %}", $result['code']);
//        $this->assertContains("{% use 'SomethingElse' %}", $result['code']);
//        $this->assertContains("{% use 'NEW-NEW-NEW' %}", $result['code']);
//        $result = $this->helper->createCode('{% extends Core:Base:HTML %}', array(
//            '{% use "new-new-new"%}',
//            "{% use 'Something:Else-Now'%}",
//        ));
//        $this->assertEquals($result['code'], $this->rawCodeBlock);
//        $result = $this->helper->createCode('Core:Base""Html', array(
//            'NEW NEW NEW',
//            'New\New\NEW',
//            'foo"Bar',
//        ));
//        $this->assertEquals($result['code'], $this->rawCodeBlock);
//    }

}
