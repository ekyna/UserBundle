<?php

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\AdminBundle\Event\ResourceEvent;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class UserEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserEvent extends ResourceEvent
{
    /**
     * Constructor.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->setResource($user);
    }

    /**
     * Returns the user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->getResource();
    }
}
