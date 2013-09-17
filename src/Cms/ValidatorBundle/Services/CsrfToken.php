<?php
/**
 * Basic Usage: instantiate a new CsrfToken object and a token is automatically stored to session
 * use getToken() to get token
 * on next request, validate passed token by re-instantiating a CsrfToken and running it's validate($token) method
 *
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 10:42 PM
 */

namespace Cms\ValidatorBundle\Services;
use \Cms\ValidatorBundle\Exceptions\ValidationException;

class CsrfToken {

    /**
     * @var string
     */
    private $hashAlgo;

    /**
     * @var
     */
    private $session;

    /**
     * Pass a session handler with set, get, and has methods
     * Pass a hash algo name. Currently supports "md5"
     * @param $session
     * @param string $hashAlgo
     */
    public function __construct($session, $hashAlgo = 'md5')
    {
        $this->setSession($session);
        $this->hashAlgo = $hashAlgo;
        if ( ! $this->session->has('CSRFToken') )
        {
            $this->createToken();
        }
    }

    /**
     * @param $session
     * @return $this
     */
    private function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * create a new token with set algo
     */
    public function createToken()
    {
        // get hash algo
        switch($this->hashAlgo){
            case 'md5':
            default:
                $token = \md5(uniqid(mt_rand(), true));
                break;
        }
        $this->session->set('CSRFToken', $token);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->session->get('CSRFToken');
    }


    public function validate($token, $throw = 'Your CSRF token is invalid. Please try again.')
    {
        if ( ! $this->session->has('CSRFToken') )
        {
            if ( $throw )
            {
                throw new ValidationException($throw);
            }
            return false;
        }
        else if ( $token === $this->session->get('CSRFToken') )
        {
            $this->session->remove('CSRFToken');
            return true;
        }
        else
        {
            if ( $throw )
            {
                throw new ValidationException($throw);
            }
            return false;
        }
    }

}