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
     * @return GroupInterface
     */
    public function findDefault()
    {
        return $this->findOneBy(array('default' => true));
    }
}
