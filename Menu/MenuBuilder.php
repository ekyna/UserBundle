<?php

namespace Ekyna\Bundle\UserBundle\Menu;

use Ekyna\Bundle\UserBundle\Extension\ExtensionRegistry;
use Knp\Menu\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class MenuBuilder
 * @package Ekyna\Bundle\UserBundle\Menu
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var ExtensionRegistry
     */
    protected $extensionRegistry;

    /**
     * @var bool
     */
    private $accountEnabled;

    /**
     * @var OptionsResolverInterface
     */
    private $optionResolver;

    /**
     * @var array
     */
    private $accountEntries;


    /**
     * Constructor.
     *
     * @param FactoryInterface         $factory
     * @param SecurityContextInterface $securityContext
     * @param ExtensionRegistry        $extensionRegistry
     * @param array                    $config
     */
    public function __construct(
        FactoryInterface $factory,
        SecurityContextInterface $securityContext,
        ExtensionRegistry $extensionRegistry,
        array $config
    ) {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->extensionRegistry = $extensionRegistry;
        $this->accountEnabled = $config['account']['enable'];

        $this->optionResolver = new OptionsResolver();
        $this->optionResolver
            ->setDefaults(array(
                'label' => null,
                'route' => null,
                'position' => 0,
            ))
            ->setRequired(array('label', 'route', 'position'))
            ->setAllowedTypes(array(
                'label' => 'string',
                'route' => 'string',
                'position' => 'int',
            ))
        ;

        $this->accountEntries = [];
        foreach ($this->extensionRegistry->getAccountEntries() as $name => $options) {
            $this->addAccountEntry($name, $options);
        }
    }

    /**
     * Adds the entry to he account menu.
     * 
     * @param string $name
     * @param array $options
     */
    public function addAccountEntry($name, array $options)
    {
        $this->accountEntries[$name] = $this->optionResolver->resolve($options);
    }

    /**
     * Creates the account menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createAccountMenu()
    {
        $menu = $this->factory->createItem('root');

        uasort($this->accountEntries, function($a, $b) {
            if ($a['position'] == $b['position']) {
                return 0;
            }
            return $a['position'] < $b['position'] ? -1 : 1;
        });

        foreach($this->accountEntries as $name => $options) {
            $menu->addChild($name, $options);
        }

        return $menu;
    }

    /**
     * Creates the user menu.
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createUserMenu()
    {
        $menu = $this->factory->createItem('root');

        if ($this->accountEnabled) {
            if (null !== $this->securityContext->getToken()) {
                if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') || $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    $user = $this->securityContext->getToken()->getUser();
                    $item = $menu->addChild($user->getEmail(), array('uri' => '#'));
                    $item->addChild('ekyna_user.account.menu.my_profile', array('route' => 'fos_user_profile_show'));
                    if ($this->securityContext->isGranted('ROLE_ADMIN')) {
                        $item->addChild('ekyna_user.account.menu.backend', array('route' => 'ekyna_admin'));
                    }
                    $item->addChild('ekyna_user.account.menu.logout', array('route' => 'fos_user_security_logout'));
                    return $menu;
                }
            }
            $menu->addChild('ekyna_user.account.menu.login', array('route' => 'fos_user_security_login'));
        }

        return $menu;
    }
}
