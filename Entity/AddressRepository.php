<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;

/**
 * Class AddressRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressRepository extends ResourceRepository
{
    public function findByUser(User $user)
    {
        return $this->findBy(array('user' => $user));
    }
}
