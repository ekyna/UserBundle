<?php

namespace Ekyna\Bundle\UserBundle\Extension;

use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class AbstractExtension
 * @package Ekyna\Bundle\UserBundle\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAdminShowTab(UserInterface $user)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountMenuEntries()
    {
        return null;
    }
}
