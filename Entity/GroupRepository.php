<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;

/**
 * Class GroupRepository
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class GroupRepository extends ResourceRepository
{
    /**
     * Returns the default user group.
     *
     * @return GroupInterface
     * @throws \RuntimeException
     */
    public function findDefault()
    {
        if (null === $group = $this->findOneBy(array('default' => true))) {
            throw new \RuntimeException('Default user group not found.');
        }
    }
}
