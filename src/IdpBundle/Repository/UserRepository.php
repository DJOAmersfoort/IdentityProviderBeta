<?php

namespace IdpBundle\Repository;

use IdpBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * This class contains utility methods to quickly find users.
 *
 * @author Roelof Roos
 */
class UserRepository extends EntityRepository
{
    /**
     * Returns the user that has the given ID
     *
     * @param  mixed $id ID to look for, can be anything
     * @return User      Resulting user, or null.
     */
    public function findByBackendId($id) : ?User
    {
        return $this->findOneBy([
            'remoteId' => $id
        ]);
    }

    /**
     * Returns **all** users that have the given email address. The address is
     * normalized before the query is run.
     *
     * @param  string $email
     * @return array         Array of found users
     */
    public function findByEmail($email) : ?array
    {
        return $this->findBy([
            'email' => trim(strtolower($email))
        ]);
    }
}
