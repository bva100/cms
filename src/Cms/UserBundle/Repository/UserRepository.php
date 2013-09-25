<?php

namespace Cms\UserBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Class UserRepository
 * @package Cms\UserBundle\Repository
 */
class UserRepository extends DocumentRepository{

    public function FindByIds($ids)
    {
        return $this->createQueryBuilder()
            ->field('id')->in($ids)
            ->getQuery()->execute();
    }

    public function FindOneByEmail($email)
    {
        return $this->createQueryBuilder()
            ->field('email')->equals($email)
            ->getQuery()->execute()->getSingleResult();
    }

}