<?php
/**
 * User: Brian Anderson
 * Date: 8/30/13
 * Time: 9:39 AM
 */

// this needs more work with a unit test. Step 1) create token using client ID and a (generated) secret 2) get token. Then to validate, separate clientId and secret and validate secret again site object.
// maybe review facebook and instagram here...
// only send token in post requests

namespace Cms\CoreBundle\Services\Api;

class Token {

    private $token;

    private $clientId;

    private $rawClientSecret;

    public function setToken($token)
    {
        $this->token = $token;
        $this->setClientIdAndRawClientSecret($token);
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setClientIdAndRawClientSecret($token)
    {
        $token = $this->base64_url_decode($token);
        $pos = strpos($token, ':');
        $this->clientId = substr($token, 0, $pos);
        $this->rawClientSecret = substr($token, $pos+1);
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getRawClientSecret()
    {
        return $this->rawClientSecret;
    }

    public function createToken($clientId, $clientSecret)
    {
        return $this->base64_url_encode($clientId.':'.$clientSecret);
    }

    public function createSecret()
    {
        return md5(uniqid());
    }
    
    public function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_.');
    }

    public function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_.', '+/='));
    }

}