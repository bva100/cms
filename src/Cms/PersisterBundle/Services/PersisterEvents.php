<?php
/**
 * User: Brian Anderson
 * Date: 6/6/13
 * Time: 4:51 PM
 */

namespace Cms\PersisterBundle\Services;

use Cms\PersisterBundle\Services\Persister;

/**
 * Class Events
 * @package Cms\PersisterBundle\Services
 */
class PersisterEvents implements InterfaceEvents {

    /**
     * @var Persister
     */
    private $persister;

    /**
     * @param Persister $persister
     */
    public function __construct(Persister $persister)
    {
        $this->setPersister($persister);
    }

    /**
     * @param \Cms\PersisterBundle\Services\Persister $persister
     * @return this
     */
    public function setPersister(Persister $persister)
    {
        $this->persister = $persister;
        return $this;
    }

    /**
     * @return \Cms\PersisterBundle\Services\Persister
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     * @param $eventArgs
     */
    public function preUpdate(\Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs)
    {
    }

    /**
     * @param $eventArgs
     */
    public function preFlush(\Doctrine\ODM\MongoDB\Event\PreFlushEventArgs $eventArgs)
    {
    }


}