<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 5:19 PM
 */

namespace Cms\PersisterBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class Persister
 * @package Cms\PersisterBundle\Services
 */
class Persister {

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * @var validation lib
     */
    private $validator;

    /**
     * @var session helper
     */
    private $session;

    /**
     * @var flashBag from session service
     */
    private $flashBag;

    /**
     * @param ObjectManager $em
     * @param $validator
     * @param $session
     * @param bool $addFlashBag
     */
    public function __construct($em, $validator, $session = null, $addFlashBag = true)
    {
        $this->setEm($em);
        $this->setValidator($validator);
        if ( isset($session) )
        {
            $this->setSession($session);
            if ( $addFlashBag )
            {
                $this->setFlashBag($this->session->getFlashBag());
            }
        }
    }

    /**
     * @param ObjectManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return ObjectManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param  $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return var
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param $flashBag
     */
    public function setFlashBag($flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @return flashBag object
     */
    public function getFlashBag()
    {
        return $this->flashBag;
    }

    /**
     * Flush
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @param $object
     * @param bool $lazy
     * @param string $onSuccess
     * @return bool
     */
    public function save($object, $lazy = false, $onSuccess = 'save complete')
    {
        // validate
        $errors = $this->validator->validate($object);
        if ( \count($errors) > 0 )
        {
            //set error messages to flashBag notices
            if ( isset($this->flashBag) )
            {
                foreach ($errors as $error) {
                    $this->flashBag->add('notices', $error->getMessage());
                }
            }
            return false;
        }
        if ( ! $object->getId() )
        {
            $this->em->persist($object);
        }
        if ( ! $lazy )
        {
            $this->flush();
        }
        if ( isset($this->flashBag) )
        {
            $this->flashBag->add('notices', $onSuccess);
        }
        return true;
    }

    /**
     * @param $object
     * @param bool $lazy
     * @return bool
     */
    public function delete($object, $lazy = false)
    {
        $this->em->remove($object);
        if ( ! $lazy )
        {
            $this->flush();
        }
        return true;
    }

    /**
     * @param $className
     * @return mixed
     */
    public function getRepo($className)
    {
        return $this->em->getRepository($className);
    }

}