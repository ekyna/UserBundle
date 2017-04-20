<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UserBundle\Event;

use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class MenuEvent
 * @package Ekyna\Bundle\UserBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class MenuEvent extends Event
{
    public const CONFIGURE_ACCOUNT = 'ekyna_user.menu.configure_account';
    public const CONFIGURE_WIDGET  = 'ekyna_user.menu.configure_widget';

    private FactoryInterface $factory;
    private ItemInterface    $menu;
    private ?UserInterface   $user;

    public function __construct(FactoryInterface $factory, ItemInterface $menu, UserInterface $user = null)
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->user = $user;
    }

    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
}
