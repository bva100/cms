<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 12:10 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\UserBundle\Document\user;

class UserController extends Controller {

    public function saveAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $email = (string)$this->getRequest()->request->get('email');
        $password = (string)$this->getRequest()->request->get('password');
        $this->get('csrfToken')->validate($token);

        $user = $id ? $this->get('persister')->getRepo('CmsCoreBundle:User')->find($id) : new User();
        if ( ! $user )
        {
            return $this->createNotFoundException('User not found');
        }
        
        if ( $email )
        {
            $user->setEmail($email);
        }
        if ( $password )
        {
            $user->setPassword($password);
        }
        $user->addRole('ROLE_USER');
        $success = $this->get('persister')->save($user);

        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.user_register'));
        }
        $this->get('force_login')->login($user);
        return $this->redirect($this->generateUrl('cms_core.app_index'));
    }


    public function registerAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        return $this->render('CmsCoreBundle:User:register.html.twig', array(
            'token' => $token,
            'notices' => $notices,
        ));
    }


}