<?php
/**
 * User: Brian Anderson
 * Date: 6/3/13
 * Time: 10:54 PM
 */

namespace Cms\UserBundle\Services;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SymfonyTokenForceLogin
 * @package Cms\UserBundle\Services
 */
class SymfonyTokenForceLogin implements InterfaceForceLogin {

    /**
     * @var
     */
    private $securityContext;

    /**
     * @var
     */
    private $firewall;

    /**
     * @param $securityContext
     * @param $firewall
     */
    public function __construct($securityContext, $firewall)
    {
        $this->setSecurityContext($securityContext);
        $this->setFirewall($firewall);
    }

    /**
     * @param $firewall
     */
    public function setFirewall($firewall)
    {
        $this->firewall = $firewall;
    }

    /**
     * @return mixed
     */
    public function getFirewall()
    {
        return $this->firewall;
    }

    /**
     * @param $securityContext
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @return mixed
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @param $user
     */
    public function login($user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), $this->firewall, $user->getRoles());
        $this->securityContext->setToken($token);
    }

}