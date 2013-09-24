<?php


namespace Cms\CoreBundle\Services\Acl;

use Cms\UserBundle\Document\User;
use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\Site;

class Helper {

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

}