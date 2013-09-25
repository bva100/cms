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
        $userId = $user->getId();
        $owner = $object->getAclOwner();
        if ( ! empty($owner) AND in_array($method, $owner['permissions']) AND $userId === $owner['id'] ){
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
            if ( $group->hasUserId($userId ) ){
                return true;
            }
        }
        $other = $object->getAclOther();
        if ( empty($other) OR in_array($method, $other['permissions']) ){
            foreach ($site->getGroups() as $group) {
                if ( $group->hasUserId($userId ) ){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Is user a super user?
     *
     * @param User $user
     * @param Site $site
     * @return bool
     */
    public function isSuper(User $user, Site $site)
    {
        $superGroup = $site->getGroupByName('supers');
        if ( $superGroup AND $superGroup->hasUserId($user->getId()) ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * A shortcut helper for creating
     *
     * @param array $ownerPermissions
     * @param array $groupPermissions
     * @param array $otherPermissions
     * @param $groupId
     * @return array
     * @throws RuntimeException
     */
    public function createAcl(array $ownerPermissions, array $groupPermissions, array $otherPermissions, $groupId)
    {
        if ( ! is_string($groupId) ){
            throw new RuntimeException('The second parameter of CreateAcl is expected to be a string representation of the group ID. '.gettype($groupId).' was passed.');
        }
        return array(
            'owner' => array(
                'id' => null,
                'permissions' => $ownerPermissions,
            ),
            'group' => array(
                'id' => $groupId,
                'permissions' => $groupPermissions,
            ),
            'other' => array(
                'permissions' => $otherPermissions,
            ),
        );
    }

}