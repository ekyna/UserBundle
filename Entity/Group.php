<?php

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\Group as BaseGroup;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

/**
 * Class Group
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Group extends BaseGroup implements GroupInterface
{
    /**
     * @var boolean
     */
    protected $default = false;

    /**
     * Constructor
     *
     * @param string $name
     * @param array $roles
     */
    public function __construct($name, array $roles = array())
    {
        parent::__construct($name, $roles);
        $this->default = false;
    }

    /**
     * Returns a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
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
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityIdentity()
    {
        return new RoleSecurityIdentity(sprintf('ROLE_GROUP_%d', $this->getId()));
    }
}
