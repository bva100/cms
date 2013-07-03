<?php
/**
 * User: Brian Anderson
 * Date: 7/2/13
 * Time: 6:51 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SessionController extends Controller {

    public function getNoticesAction($_format)
    {
        $notices = $this->get('session')->getFlashBag()->get('notices');
        if ( $_format === 'json' )
        {
            $response = new Response(json_encode($notices), 200);
            $response->headers->set('Content-Type', 'application/json');
        }
        return $response;
    }

}