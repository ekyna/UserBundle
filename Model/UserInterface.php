<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Model;

use Ekyna\Component\User\Model\UserInterface as BaseUser;

/**
 * Interface UserInterface
 * @package Ekyna\Bundle\UserBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface UserInterface extends BaseUser
{
    /**
     * Sets whether to send the creation email or not.
     * (non mapped)
     *
     * @return $this|UserInterface
     */
    public function setSendCreationEmail(bool $send): UserInterface;

    /**
     * Returns whether to send the creation email or not.
     * (non mapped)
     */
    public function getSendCreationEmail(): bool;
}
