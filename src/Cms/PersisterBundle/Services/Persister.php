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
     * @param ObjectManager $em
     * @param $validator
     */
    public function __construct($em, $validator)
    {
        $this->setEm($em);
        $this->setValidator($validator);
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
     * @param array $flash
     * @throws \InvalidArgumentException
     */
    private function validateFlashArray(array $flash)
    {
        if ( empty($flash) )
        {
            return;
        }
        else if ( ! isset($flash['flashBag']) )
        {
            throw new \InvalidArgumentException('flash array is missing a flashBag key value pair');
        }
        else if ( ! isset($flash['onSuccess']) )
        {
            throw new \InvalidArgumentException('flash array is missing an onSuccess key value pair');
        }

    }

    /**
     * @param $object
     * @param bool $lazy
     * @param array $flash
     * @return bool
     */
    public function save($object, $lazy = false, array $flash = array())
    {
        // validate
        $this->validateFlashArray($flash);
        $errors = $this->validator->validate($object);
        if ( \count($errors) > 0 )
        {
            //set error messages to flashBag notices
            if ( ! empty($flash) )
            {
                foreach ($errors as $error) {
                    $flash['flashBag']->set('notices', $error->getMessage());
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
            $this->em->flush();
        }
        if ( ! empty($flash) )
        {
            $flash['flashBag']->set('notices', $flash['onSuccess']);
        }
        return true;
    }
}