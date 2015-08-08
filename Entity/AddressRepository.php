<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class AddressRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressRepository extends ResourceRepository
{
    /**
     * Finds addresses by user.
     *
     * @param UserInterface $user
     * @return array|\Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findByUser(UserInterface $user)
    {
        return $this->findBy(array('user' => $user));
    }
}
