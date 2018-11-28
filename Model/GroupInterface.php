<?php

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Component\Resource\Model as RM;
use FOS\UserBundle\Model\GroupInterface as BaseGroupInterface;

/**
 * Interface GroupInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface GroupInterface extends BaseGroupInterface, RM\ResourceInterface, RM\SortableInterface
{
    /**
     * Set whether the group is the default one
     *
     * @param boolean $default
     * @return GroupInterface|$this
     */
    public function setDefault($default);

    /**
     * Get whether the group is the default one
     *
     * @return boolean
     */
    public function getDefault();
}
