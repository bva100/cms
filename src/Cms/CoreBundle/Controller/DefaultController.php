<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $template = new Template();
        $template->setName('Summit');
        $template->setContent('{% extends \'coreBase\' %} {% block body %}<h1>Hello World!</h1>!{% endblock %}');
        $this->get('persister')->save($template);
    }
}
