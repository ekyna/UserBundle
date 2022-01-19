<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Entity;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Component\User\Model\AbstractUser;

/**
 * Class User
 * @package Ekyna\Bundle\UserBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class User extends AbstractUser implements UserInterface
{
    /** ------------ (non mapped) ------------ */
    protected bool $sendCreationEmail = false;


    /**
     * @inheritDoc
     */
    public function setSendCreationEmail(bool $send): UserInterface
    {
        $this->sendCreationEmail = $send;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSendCreationEmail(): bool
    {
        return $this->sendCreationEmail;
    }
}
