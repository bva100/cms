<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 5:19 PM
 */

namespace Cms\PersisterBundle\Services;

use Cms\CoreBundle\Document\Base;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class Persister
 * @package Cms\PersisterBundle\Services
 */
class Persister {

    /**
     * Entity Manager
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $em;

    /**
     * Event Manager
     *
     * @var object $evm
     */
    private $evm;

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
        $this->setEvm($this->em->getEventManager());
        $this->setValidator($validator);
        if ( isset($session) )
        {
            $this->setSession($session);
            if ( $addFlashBag )
            {
                $this->setFlashBag($this->session->getFlashBag());
            }
        }
        // add eventListeners. Be sure each of these methods is included in InterfaceEvents
//        $this->evm->addEventListener(\Doctrine\ODM\MongoDB\Events::preFlush, new PersisterEvents($this));
//        $this->evm->addEventListener(\Doctrine\ODM\MongoDB\Events::preUpdate, new PersisterEvents($this));
    }

    /**
     * @param ObjectManager $em
     * @return $this
     */
    public function setEm($em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param \Doctrine\Common\EventManager $evm
     * @return $this
     */
    public function setEvm(\Doctrine\Common\EventManager $evm)
    {
        $this->evm = $evm;
        return $this;
    }

    /**
     * @return \Doctrine\Common\EventManager
     */
    public function getEvm()
    {
        return $this->evm;
    }

    /**
     * @param  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
        return $this;
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
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
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
     * @return $this
     */
    public function setFlashBag($flashBag)
    {
        $this->flashBag = $flashBag;
        return $this;
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
     * Persists an ODM object. onSuccess defines flashBagMess                  ge on success. Set the verboseErrors param to true to get explicit error messages returned as a string.
     *
     * @param $object
     * @param bool $lazy
     * @param string $onSuccess
     * @param bool $verboseErrors
     * @return bool|string
     */
    public function save($object, $lazy = false, $onSuccess = 'save complete', $verboseErrors = false)
    {
        $errors = $this->validator->validate($object);
        if ( \count($errors) > 0 )
        {
            if ( $verboseErrors )
            {
                return $errors[0];
            }
            if ( isset($this->flashBag) )
            {
                foreach ($errors as $error) {
                    $this->flashBag->add('notices', $error->getMessage());
                }
            }
            return false;
        }
        if ( $object instanceof Base AND $object->getId()){
            $object->setUpdated(time());
        }
        if ( ! $object->getId() )
        {
            if ( $object instanceof Base ){
                $object->setCreated(time());
            }
            $this->em->persist($object);
        }
        if ( ! $lazy )
        {
            $this->flush();
        }
        if ( isset($this->flashBag) AND $onSuccess )
        {
            $this->flashBag->add('notices', $onSuccess);
        }
        return true;
    }

    /**
     * Removes an object from associated storage. OnSuccess defines the string to in flashBagNotice. Set the verboseErrors param to true to get explicit error messages returned as a string.
     *
     * @param $object
     * @param bool $lazy
     * @param string $onSuccess
     * @param bool $verboseErrors
     * @return bool | string
     */
    public function delete($object, $lazy = false, $onSuccess = 'deleted', $verboseErrors = false)
    {
        $this->em->remove($object);
        if ( ! $lazy )
        {
            $this->flush();
        }
        if ( isset($this->flashBag) and $onSuccess )
        {
            $this->flashBag->add('notices', $onSuccess);
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