<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Document\Asset;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        echo '<pre>', \var_dump($name); die();
    }
}
