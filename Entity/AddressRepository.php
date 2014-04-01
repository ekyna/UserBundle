<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;

/**
 * AddressRepository
 */
class AddressRepository extends ResourceRepository
{
    public function findByUser(User $user)
    {
        return $this->findBy(array('user' => $user));
    }
}
