<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 6:32 PM
 */

namespace Cms\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package Cms\UserBundle\Document
 * @MongoDB\Document(collection="users", repositoryClass="Cms\UserBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable{

    /**
     * @var array of salts
     */
    private $saltGroup = array(
        1 => '190qrg9jTkJZAfYJi7Ho',
        2 => 'GeBteR7pdS1xcDCZE40dY',
        3 => 'HsWmG9w3CpXEp81Qkkiq',
    );

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     */
    private $email;

    /**
     * @MongoDB\Int
     */
    private $saltGroupIndex;

    /**
     * @MongoDB\String
     */
    private $password;

    /**
     * @MongoDB\Collection
     */
    private $roles;

    /**
     * @MongoDB\Hash
     */
    private $name;

    /**
     * @MongoDB\Collection
     */
    private $ips;

    /**
     * @MongoDB\Int
     */
    private $utcOffset;

    /**
     * @MongoDB\String
     */
    private $locale;

    /**
     * @MongoDB\Hash
     */
    private $login;

    /**
     * @MongoDB\String
     */
    private $state;

    /**
     * @MongoDB\Collection
     */
    private $siteIds;

    /**
     * Set default salt group index and default state
     */
    public function __construct($saltGroupIndex = 1, $state = 'active')
    {
        $this->setSaltGroupIndex(1);
        $this->roles = array('ROLE_USER');
        $this->name = array();
        $this->login = array('count' => 0);
        $this->state = 'active';
        $this->ips = array();
        $this->siteIds = array();
    }

    /**
     * Set id
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns email instead of username. Used to satisfy interface requirements and remember-me functionality
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Set saltGroupIndex
     *
     * @param int $saltGroupIndex
     * @return self
     */
    public function setSaltGroupIndex($saltGroupIndex)
    {
        $this->saltGroupIndex = $saltGroupIndex;
        return $this;
    }

    /**
     * Get saltGroupIndex
     *
     * @return int $saltGroupIndex
     */
    public function getSaltGroupIndex()
    {
        if ( ! isset($this->saltGroupIndex) )
        {
            $this->setSaltGroupIndex(1);
        }
        return $this->saltGroupIndex;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        $index = $this->getSaltGroupIndex();
        return $this->saltGroup[$index];
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @param $role
     * @return $this
     */
    public function removeRole($role)
    {
        $roles = array_flip($this->roles);
        if ( isset($roles[$role]) )
        {
            unset($roles[$role]);
            $this->roles = array_flip($roles);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeAllRoles()
    {
        $this->roles = array();
        return $this;
    }

    /**
     * Cannot directly set roles, must use addRole method
     */
    public function setRoles()
    {
    }

    /**
     * Get roles
     *
     * @return collection $roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ( $this->state === 'active' )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Erase which properties on logout?
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }


    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list ($this->id) = unserialize($serialized);
    }

    /**
     * @param array $nameArray
     * @return $this
     */
    public function setName(array $nameArray = array())
    {
        $name = array();
        if ( isset($nameArray['first']) )
        {
            $name['first'] = \ucfirst($nameArray['first']);
        }
        if ( isset($nameArray['last']) )
        {
            $name['last'] = \ucfirst($nameArray['last']);
        }
        if ( isset($nameArray['middle']) )
        {
            $name['middle'] = \ucfirst($nameArray['middle']);
        }
        if ( isset($nameArray['prefix']) )
        {
            $name['prefix'] = \ucfirst($nameArray['prefix']);
        }
        if ( isset($nameArray['suffix']) )
        {
            $name['suffix'] = \ucfirst($nameArray['suffix']);
        }
        $this->name = $name;
        return $this;
    }

    /**
     * @param null $type
     * @return array|string
     */
    public function getName($type = null)
    {
        if ( ! isset($type) )
        {
            return $this->name;
        }
        else
        {
            switch($type){
                case 'first_last':
                    if ( isset($this->name['first']) AND isset($this->name['last']) )
                    {
                        return $this->name['first'].' '.$this->name['last'];
                    }
                    break;
                case 'short':
                    if ( isset($this->name['first']) AND isset($this->name['last']) )
                    {
                        return $this->name['first'].' '.$this->name['last'][0];
                    }
                    break;
                case 'first':
                    if ( isset($this->name['first']) )
                    {
                        return $this->name['first'];
                    }
                    break;
                case 'last':
                    if ( isset($this->name['last']) )
                    {
                        return $this->name['last'];
                    }
                    break;
                case 'middle':
                    if ( isset($this->name['middle']) )
                    {
                        return $this->name['middle'];
                    }
                    break;
                case 'prefix':
                    if ( isset($this->name['prefix']) )
                    {
                        return $this->name['prefix'];
                    }
                    break;
                case 'suffix':
                    if ( isset($this->name['suffix']) )
                    {
                        return $this->name['suffix'];
                    }
                    break;
                default:
                    return '';
            }
        }
    }

    /**
     * Set ips
     *
     * @param array $ips
     * @return self
     */
    public function setIps(array $ips)
    {
        $this->ips = $ips;
        return $this;
    }

    /**
     * Add an IP to the IP collection
     *
     * @param string $ip
     * @return $this
     */
    public function addIp($ip)
    {
        if ( ! is_string($ip) OR in_array($ip, $this->ips) ){
            return $this;
        }
        $this->ips[] = $ip;
        return $this;
    }

    /**
     * Remove an IP from the IP collection
     *
     * @param string $ip
     * @return $this
     */
    public function removeIp($ip)
    {
        $keys = array_keys($this->ips, $ip);
        foreach ($keys as $key)
        {
            unset($this->ips[$key]);
            $this->ips = array_values($this->ips);
        }
        return $this;
    }

    /**
     * Get ip
     *
     * @return array $ip
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * Does user have IP?
     *
     * @param string $ip
     * @return bool
     */
    public function hasIp($ip)
    {
        return in_array($ip, $this->ips);
    }

    /**
     * Set utcOffset
     *
     * @param int $utcOffset
     * @return self
     */
    public function setUtcOffset($utcOffset)
    {
        $this->utcOffset = $utcOffset;
        return $this;
    }

    /**
     * Get utcOffset
     *
     * @return int $utcOffset
     */
    public function getUtcOffset()
    {
        return $this->utcOffset;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get locale
     *
     * @return string $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return $this
     */
    public function recordLogin()
    {
        $login = array();
        if ( isset($this->login['count']) )
        {
            $login['count'] = ++$this->login['count'];;
        }
        else
        {
            $login['count'] = 1;
        }
        $login['last'] = time();
        $this->login = $login;
        return $this;
    }

    /**
     * Get login
     *
     * @param null $type
     * @return hash $login
     */
    public function getLogin($type = null)
    {
        if ( ! isset($type) )
        {
            return $this->login;
        }
        else if ( $type === 'count' )
        {
            return $this->login['count'];
        }
        else if ( $type === 'last' )
        {
            return $this->login['last'];
        }
    }

    /**
     * @param string $siteId
     * @return $this
     */
    public function addSiteId($siteId)
    {
        if ( ! is_string($siteId) OR in_array($siteId, $this->siteIds) ){
            return $this;
        }
        $this->siteIds[] = $siteId;
        return $this;
    }

    /**
     * @param string $siteId
     * @return $this
     */
    public function removeSiteId($siteId)
    {
        $keys = array_keys($this->siteIds, $siteId);
        foreach ($keys as $key) {
            unset($this->siteIds[$key]);
            $this->siteIds = array_values($this->siteIds);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function getSiteIds()
    {
        return $this->siteIds;
    }

    /**
     * @param string $siteId
     * @return bool
     */
    public function hasSiteId($siteId)
    {
        return in_array($siteId, $this->siteIds);
    }
    
}
