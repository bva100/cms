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
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $email = (string)$this->getRequest()->request->get('email');
        $password = (string)$this->getRequest()->request->get('password');
        $accountPlan = (string)$this->getRequest()->query->get('accountPlan');
        $user = $id ? $this->get('persister')->getRepo('CmsCoreBundle:User')->find($id) : new User();
        if ( ! $user ){
            return $this->createNotFoundException('User not found');
        }
        if ( $email ){
            $user->setEmail($email);
        }
        if ( $password ){
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password,$user->getSalt()));
        }
        if ( $accountPlan ){
            $user->setAccountPlan($accountPlan);
        }
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

    public function registerAction($accountPlan)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $firstName = (string)$this->getRequest()->query->get('firstName');
        $lastName = (string)$this->getRequest()->query->get('lastName');
        $email = (string)$this->getRequest()->query->get('email');
        if ( ! $accountPlan ){
            $accountPlan = 'free';
        }
        return $this->render('CmsCoreBundle:User:register.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'accountPlan' => $accountPlan,
        ));
    }

    public function createAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $accountPlan = (string)$this->getRequest()->request->get('accountPlan');
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
        $user->setAccountPlan($accountPlan);
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
        switch($accountPlan){
            case 'enterprise':
                return $this->redirect($this->generateUrl('cms_core.app_thanks_enterprise'));
                break;
            case 'premium':
                return $this->redirect($this->generateUrl('cms_core.app_thanks_premium'));
                break;
            case 'free':
            default:
                return $this->redirect($this->generateUrl('cms_core.app_thanks_free'));
                break;
        }
    }


}