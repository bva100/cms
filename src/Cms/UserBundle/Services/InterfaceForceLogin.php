<?php
/**
 * User: Brian Anderson
 * Date: 6/3/13
 * Time: 10:53 PM
 */

namespace Cms\UserBundle\Services;


/**
 * Class InterfaceForceLogin
 * @package Cms\UserBundle\Services
 */
interface InterfaceForceLogin {

    /**
     * @param $user
     * @return void
     */
    public function login($user);

}