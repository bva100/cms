<?php
/**
 * User: Brian Anderson
 * Date: 8/30/13
 * Time: 9:39 AM
 */

namespace Cms\CoreBundle\Services\Api;

use \Cms\CoreBundle\Document\Site;
use \RuntimeException;

class Token {

    const CURRENT_VERSION = '1';
    const CYPHER = MCRYPT_RIJNDAEL_256;
    const MODE   = MCRYPT_MODE_CBC;
    const KEY    = 'Hs82Bsp1S9zw0';

    private $token;

    private $clientId;

    private $rawClientSecret;

    private $version;

    private $site;

    /**
     * Set's token, as well as client id and secret.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->setClientIdAndRawClientSecret($token);
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set client Id and raw client secret. Accounts for different token versions.
     *
     * @param $token
     * @return $this
     */
    public function setClientIdAndRawClientSecret($token)
    {
        $token = $this->decrypt($token);
        $this->setVersionWithToken($token);
        switch($this->version){
            case 1:
                $token = $this->stripVersionFromToken($token, $this->version);
                $pos = (int)strpos($token, ':');
                $this->clientId = substr($token, 0, $pos);
                $this->rawClientSecret = substr($token, $pos+1);
                break;
            default:
                $this->clientId = '';
                $this->rawClientSecret = '';
                break;
        }
        return $this;
    }

    /**
     * Set's version by using token
     *
     * @param $token
     */
    private function setVersionWithToken($token)
    {
        $versionPos = (int)strpos($token, ':v=');
        $this->version = substr($token, ($versionPos+3));
    }

    /**
     * get the raw token without the version
     *
     * @param $token
     * @param $version
     * @return string
     */
    private function stripVersionFromToken($token, $version)
    {
        return $version ? str_replace(':v='.$version, '', $token) : '' ;
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * get raw client secret
     *
     * @return string
     */
    public function getRawClientSecret()
    {
        return $this->rawClientSecret;
    }

    /**
     * get token version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Inject a site Document/Entity object
     *
     * @param Site $site
     * @return $this
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * Get an injected site Document/Entity object
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Validate access token against an injected document/entity
     *
     * @param string $format
     * @return bool
     * @throws \RuntimeException
     * @throws ApiException
     */
    public function validateAuth($format = 'html')
    {
        if ( $this->site->getId() !== $this->clientId )
        {
            throw new RuntimeException('Site id and client id do not match. This error occurred while validating Auth and usually means the wrong site was injected into the api token service');
        }
        if ( $this->site->getClientSecret() !== $this->rawClientSecret )
        {
            throw new ApiException(10001, $format);
        }
        return true;
    }

    /**
     * Create a new token by passing in a client's ID and secret. Defaults to current_version of token format.
     *
     * @param string $clientId
     * @param  string $clientSecret
     * @param string $version
     * @return string
     * @throws \RuntimeException
     */
    public function createToken($clientId, $clientSecret, $version = '')
    {
        if ( ! $version )
        {
            $version = self::CURRENT_VERSION;
        }
        switch($version){
            case '1':
                $str = $clientId.':'.$clientSecret.':v='.self::CURRENT_VERSION;
                $token = $this->encrypt($str);
                break;
            default:
                throw new RuntimeException('Unable to generate valid access token');
                break;
        }
        return $token;
    }

    /**
     * Create a client secret. Makes use of dev/urand function described in http://codeascraft.com/2012/07/19/better-random-numbers-in-php-using-devurandom
     *
     * @return string
     * @throws \RuntimeException
     */
    public function createSecret()
    {
        $min = 0;
        $max = 0x7FFFFFFF;
        $diff = $max - $min;
        if ($diff < 0 || $diff > 0x7FFFFFFF) {
            throw new RuntimeException("Bad range");
        }
        $bytes = mcrypt_create_iv(4, MCRYPT_DEV_URANDOM);
        if ($bytes === false || strlen($bytes) != 4) {
            throw new RuntimeException("Unable to get 4 bytes");
        }
        $ary = unpack("Nint", $bytes);
        $val = $ary['int'] & 0x7FFFFFFF;   // 32-bit safe
        $fp = (float) $val / 2147483647.0; // convert to [0,1]
        $rand =  round($fp * $diff) + $min;
        return substr(hash('sha512', $rand), -10);
    }

    /**
     * Encrypt token and use url base base64
     *
     * @param $plaintext
     * @return string
     */
    public function encrypt($plaintext)
    {
        $td = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, self::KEY, $iv);
        $crypttext = mcrypt_generic($td, $plaintext);
        mcrypt_generic_deinit($td);
        return $this->base64_url_encode($iv.$crypttext);
    }

    /**
     * Decrypt token from url base 64
     *
     * @param $crypttext
     * @return string
     */
    public function decrypt($crypttext)
    {
        $crypttext = $this->base64_url_decode($crypttext);
        $plaintext = '';
        $td        = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $ivsize    = mcrypt_enc_get_iv_size($td);
        $iv        = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        if ($iv)
        {
            mcrypt_generic_init($td, self::KEY, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        return trim($plaintext);
    }

    /**
     * Base64 encodes a string, then the string url safe
     *
     * @param string $input
     * @return string
     */
    public function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_.');
    }

    /**
     * Takes a base64-url_encoded string and decodes it
     *
     * @param string $input
     * @return string
     */
    public function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_.', '+/='));
    }

}