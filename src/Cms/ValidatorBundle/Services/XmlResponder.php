<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 10:33 PM
 */

namespace Cms\ValidatorBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class XmlResponder {

    public function execute(Request $request, $success, array $options = array('onSuccess' => 'success', 'onFail' => 'failed', 'format' => 'text'))
    {
        if ( ! isset($options['onSuccess']) ){
            $options['onSuccess'] = 'success';
        }
        if ( ! isset($options['onFail']) ){
            $options['onFail'] = 'failed';
        }
        if ( ! isset($options['format']) ){
            $options['format'] = 'text';
        }
        if ( ! $request->isXmlHttpRequest() )
        {
            return false;
        }
        if ( $success )
        {
            $response = new Response($options['onSuccess']);
            $response->setStatusCode(200);
        }
        else
        {
            $response = new Response($options['onFail']);
            $response->setStatusCode(500);
        }
        return $response;
    }

}