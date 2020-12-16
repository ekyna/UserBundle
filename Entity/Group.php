<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Component\Resource\Model\SortableTrait;
use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Class Group
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Group extends BaseGroup implements GroupInterface
{
    use SortableTrait;

    /**
     * @var boolean
     */
    protected $default = false;


    /**
     * Constructor
     *
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name = null, array $roles = [])
    {
        parent::__construct($name, $roles);
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name ?: 'New group';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDefault()
    {
        return $this->default;
    }
}
