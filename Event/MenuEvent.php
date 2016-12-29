<?php

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
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
    const CONFIGURE_ACCOUNT = 'ekyna_user.menu.configure_account';
    const CONFIGURE_WIDGET  = 'ekyna_user.menu.configure_widget';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ItemInterface
     */
    private $menu;

    /**
     * @var UserInterface
     */
    private $user;


    /**
     * Constructor.
     *
     * @param FactoryInterface $factory
     * @param ItemInterface    $menu
     * @param UserInterface    $user
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, UserInterface $user = null)
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->user = $user;
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

    /**
     * Returns the user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
