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
        $this->helper->setRawCode($this->rawCodeBlock);
        $this->assertFalse($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsExtends
     */
    public function testDoesContainExtends()
    {
        $this->helper->setRawCode($this->codeBlock);
        $this->assertTrue($this->helper->containsExtends());
    }

    /**
     * @covers Cms\CoreBundle\Services\TemplateLoader\Helper::containsUses
     */
    public function testDoesNotContainUses()
    {
        $this->helper->setRawCode($this->codeBlock);
        $this->assertTrue($this->helper->containsUses());
    }
    
}
