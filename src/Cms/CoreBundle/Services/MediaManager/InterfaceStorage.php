<?php
/**
 * User: Brian Anderson
 * Date: 6/26/13
 * Time: 12:17 AM
 */

namespace Cms\CoreBundle\Services\MediaManager;


interface InterfaceStorage {

    /**
     * Get the storage api
     *
     * @return mixed
     */
    public function getStorage();

}