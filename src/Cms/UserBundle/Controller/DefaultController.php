<?php

namespace Cms\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\UserBundle\Document\User;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return new Response('this is admin');
    }

    public function readUserAction()
    {
        $user = $this->get('persister')->getRepo('CmsUserBundle:User')->find('51acfbc518a516fd78000012');
        echo '<pre>', \var_dump($user->getName('last')); die();
    }

    public function createUserAction()
    {
        $user = new User();
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);

        $user->setEmail('coco@doggiewoof.com');
        $user->setSaltGroupIndex(2);
        $user->addRole('ROLE_USER');
        $user->addRole('ROLE_ADMIN');
        $user->setName( array('first' => 'coco', 'last' => 'peirre') );
        $user->setPassword($encoder->encodePassword('bark', $user->getSalt()));

        $this->get('persister')->save($user);

        return new Response('user with email '.$user->getEmail().' has been saved');
    }

    public function forceLoginAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user = $dm->getRepository('AcmeUserBundle:User')->find($id);
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'regular', $user->getRoles());
        $this->get('security.context')->setToken($token);
    }
}
