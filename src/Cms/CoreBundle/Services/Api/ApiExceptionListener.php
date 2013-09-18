<?php
/**
 * User: Brian Anderson
 * Date: 9/17/13
 * Time: 6:31 PM
 */

namespace Cms\CoreBundle\Services\Api;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Cms\CoreBundle\Services\Api\ApiException;

class ApiExceptionListener {

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ( $exception instanceof ApiException )
        {
            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $event->setResponse($response);
        }

    }

}