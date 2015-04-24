<?php

namespace Ekyna\Bundle\UserBundle\Extension;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Ekyna\Bundle\UserBundle\Extension\Admin\ShowTabInterface;

/**
 * Class ExtensionRegistry
 * @package Ekyna\Bundle\UserBundle\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExtensionRegistry
{
    /**
     * @var array|ExtensionInterface[]
     */
    private $extensions;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->extensions = array();
    }

    /**
     * Adds the extension.
     *
     * @param ExtensionInterface $extension
     */
    public function addExtension(ExtensionInterface $extension)
    {
        if (array_key_exists($name = $extension->getName(), $this->extensions)) {
            throw new \RuntimeException(sprintf('UserExtension "%s" is already registered.', $name));
        }

        $this->extensions[$name] = $extension;
    }

    /**
     * Returns the extension.
     *
     * @return array|ExtensionInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Returns the show admin tabs.
     *
     * @param UserInterface $user
     * @return array
     */
    public function getShowAdminTabs(UserInterface $user)
    {
        $tabs = [];
        foreach ($this->extensions as $name => $extension) {
            if (null !== $tab = $extension->getAdminShowTab($user)) {
                $tabs[$name] = $tab;
            }
        }
        uasort($tabs, function (ShowTabInterface $a, ShowTabInterface $b) {
            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }
            return $a->getPosition() > $b->getPosition() ? 1 : -1;
        });
        return $tabs;
    }

    /**
     * Returns the account entries.
     *
     * @return array
     */
    public function getAccountEntries()
    {
        $entries = [];
        foreach ($this->extensions as $name => $extension) {
            if (null !== $e = $extension->getAccountMenuEntries()) {
                $entries = array_merge($entries, $e);
            }
        }
        return $entries;
    }
}
