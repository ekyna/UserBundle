<?php

namespace Ekyna\Bundle\UserBundle\Extension;

use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Interface ExtensionInterface
 * @package Ekyna\Bundle\UserBundle\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ExtensionInterface
{
    /**
     * Returns the administration show tabs.
     *
     * @param UserInterface $user
     * @return Admin\ShowTabInterface|null
     */
    public function getAdminShowTab(UserInterface $user);

    /**
     * Returns the front/account menu entries.
     *
     * @return array|null
     */
    public function getAccountMenuEntries();

    /**
     * Returns the extension name.
     *
     * @return string
     */
    public function getName();
}
