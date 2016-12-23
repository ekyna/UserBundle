<?php

namespace Ekyna\Bundle\UserBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MenuEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class MenuEvent extends Event
{
    const CONFIGURE = 'ekyna_user.menu.configure';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ItemInterface
     */
    private $menu;


    /**
     * Constructor.
     *
     * @param FactoryInterface $factory
     * @param ItemInterface    $menu
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu)
    {
        $this->factory = $factory;
        $this->menu = $menu;
    }

    /**
     * Returns the menu factory.
     *
     * @return \Knp\Menu\FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Returns the menu item.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
