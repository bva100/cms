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
 * @MongoDB\Document(collection="users")
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
     * @MongoDB\Hash
     */
    private $roles;

    /**
     * @MongoDB\String
     */
    private $state;

    /**
     * Set default salt group index and default state
     */
    public function __construct($saltGroupIndex = 1, $state = 'active')
    {
        $this->setSaltGroupIndex(1);
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
     * Set username as email
     *
     * @param string $email
     * @return self
     */
    public function setUsername($email)
    {
        $this->setEmail($email);
        return $this;
    }

    /**
     * Get username as email
     *
     * @return string $email
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
     * Set roles
     *
     * @param hash $roles
     * @return self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Get roles
     *
     * @return hash $roles
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
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list ($this->id) = unserialize($serialized);
    }
}
