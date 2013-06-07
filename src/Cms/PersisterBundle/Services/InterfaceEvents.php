<?php
/**
 * User: Brian Anderson
 * Date: 6/6/13
 * Time: 4:51 PM
 */

namespace Cms\PersisterBundle\Services;

use Cms\PersisterBundle\Services\Persister;

interface InterfaceEvents {

    /**
     * Set persister
     *
     * @param Persister $persister
     * @return this
     */
    public function setPersister(Persister $persister);

    /**
     * Method to run after updating an object which has already been persisted with an id
     */
    public function preUpdate(\Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs);

    /**
     * Method to run before flushing
     */
    public function preFlush(\Doctrine\ODM\MongoDB\Event\PreFlushEventArgs $eventArgs);

}