<?php

namespace Ekyna\Bundle\UserBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * MenuBuilder
 */
class MenuBuilder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Create account menu
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * 
     * @return \Knp\Menu\ItemInterface
     */
    public function createAccountMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Mon profil', array('route' => 'fos_user_profile_show'));
        $menu->addChild('Changer mon mot de passe', array('route' => 'fos_user_change_password'));
        $menu->addChild('Mes adresses', array('route' => 'ekyna_user_address_list'));

        return $menu;
    }
}
