<?php

namespace Ekyna\Bundle\UserBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * MenuBuilder
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MenuBuilder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $entries;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->entries = array(
        	'profil' => array(
        	    'label' => 'ekyna_user.account.menu.profile',
        	    'route' => 'fos_user_profile_show',
            ),
        	'password' => array(
        	    'label' => 'ekyna_user.account.menu.password',
        	    'route' => 'fos_user_change_password',
            ),
        	'addresses' => array(
        	    'label' => 'ekyna_user.account.menu.address',
        	    'route' => 'ekyna_user_address_list',
            ),
        );
    }

    /**
     * Adds a menu entry
     * 
     * @param unknown $name
     * @param array $options
     */
    public function addEntry($name, array $options)
    {
        $this->entries[$name] = $options;
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

        foreach($this->entries as $name => $options) {
            $menu->addChild($name, $options);
        }

        return $menu;
    }
}
