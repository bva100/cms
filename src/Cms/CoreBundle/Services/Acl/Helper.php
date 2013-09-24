<?php


namespace Cms\CoreBundle\Services\Acl;

use Cms\UserBundle\Document\User;
use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\Site;

class Helper {

    /**
     * Check if user has access to a specific method (r, w, x) for a given site's object
     *
     * @param User $user
     * @param string $method
     * @param Base $object
     * @param Site $site
     * @return bool
     */
    public function hasPermission(User $user, $method, Base $object, Site $site)
    {
        $other = $object->getAclOther();
        if ( empty($other) OR in_array($method, $other['permissions']) ){
            return true;
        }
        $owner = $object->getAclOwner();
        if ( ! empty($owner) AND in_array($method, $owner['permissions']) AND $user->getId() === $owner['id'] ){
            return true;
        }
        if ( $this->isSuper($user, $site) ){
            return true;
        }
        $groupAcl = $object->getAclGroup();
        if ( ! empty($groupAcl) AND in_array($method, $groupAcl['permissions']) ){
            $group = $site->getGroup($groupAcl['id']);
            if ( ! $group ){
                return false;
            }
            if ( $group->hasUserId($user->getId()) ){
                return true;
            }
        }
        return false;
    }

    public function isSuper(User $user, Site $site)
    {
        $superGroup = $site->getGroupByName('super');
        if ( $superGroup AND $superGroup->hasUserId($user->getId()) ){
            return true;
        }else{
            return false;
        }
    }

}