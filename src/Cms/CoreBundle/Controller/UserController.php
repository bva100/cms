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
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password,$user->getSalt()));
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
        $firstName = (string)$this->getRequest()->query->get('firstName');
        $lastName = (string)$this->getRequest()->query->get('lastName');
        $email = (string)$this->getRequest()->query->get('email');
        $accountType = (string)$this->getRequest()->query->get('account_type');
        if ( ! $accountType ){
            $accountType = 'free';
        }
        return $this->render('CmsCoreBundle:User:register.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'accountType' => $accountType,
        ));
    }

    public function createAction()
    {
//        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $accountType = (string)$this->getRequest()->request->get('accountType');
        $firstName = (string)$this->getRequest()->request->get('firstName');
        $lastName = (string)$this->getRequest()->request->get('lastName');
        $email = (string)$this->getRequest()->request->get('email');
        $rawPassword = (string)$this->getRequest()->request->get('password');

        $user = new User();
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);

        $user->setName(array('first' => $firstName, 'last' => $lastName));
        $user->setEmail($email);
        $user->setPassword($encoder->encodePassword($rawPassword,$user->getSalt()));
        $user->addRole('ROLE_USER');
        $success = $this->get('persister')->save($user);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.user_register', array(
                'account_type' => $accountType,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
            )));
        }
        $this->get('force_login')->login($user);
        return $this->redirect($this->generateUrl('cms_core.app_index'));
    }


}