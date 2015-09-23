<?php

namespace Ekyna\Bundle\UserBundle\Menu;

use Ekyna\Bundle\UserBundle\Extension\ExtensionRegistry;
use Knp\Menu\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @var AuthorizationCheckerInterface
     */
    protected $authorization;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var ExtensionRegistry
     */
    protected $extensionRegistry;

    /**
     * @var bool
     */
    private $accountEnabled;

    /**
     * @var OptionsResolver
     */
    private $optionResolver;

    /**
     * @var array
     */
    private $accountEntries;


    /**
     * Constructor.
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorization
     * @param TokenStorageInterface         $tokenStorage
     * @param ExtensionRegistry             $extensionRegistry
     * @param array                         $config
     */
    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $authorization,
        TokenStorageInterface $tokenStorage,
        ExtensionRegistry $extensionRegistry,
        array $config
    ) {
        $this->factory = $factory;
        $this->authorization = $authorization;
        $this->tokenStorage  = $tokenStorage;
        $this->extensionRegistry = $extensionRegistry;
        $this->accountEnabled = $config['account']['enable'];

        $this->optionResolver = new OptionsResolver();
        $this->optionResolver
            ->setDefaults([
                'label' => null,
                'route' => null,
                'position' => 0,
            ])
            ->setRequired(['label', 'route', 'position'])
            ->setAllowedTypes('label',  'string')
            ->setAllowedTypes('route',  'string')
            ->setAllowedTypes('position',  'int')
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
            if (null !== $token = $this->tokenStorage->getToken()) {
                if ($this->authorization->isGranted('IS_AUTHENTICATED_FULLY') || $this->authorization->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    /** @var \Ekyna\Bundle\UserBundle\Model\UserInterface $user */
                    $user = $token->getUser();
                    $item = $menu->addChild($user->getEmail(), ['uri' => '#']);
                    $item->addChild('ekyna_user.account.menu.my_profile', ['route' => 'fos_user_profile_show']);
                    if ($this->authorization->isGranted('ROLE_ADMIN')) {
                        $item->addChild('ekyna_user.account.menu.backend', ['route' => 'ekyna_admin']);
                    }
                    $item->addChild('ekyna_user.account.menu.logout', ['route' => 'fos_user_security_logout']);
                    return $menu;
                }
            }
            $menu->addChild('ekyna_user.account.menu.login', ['route' => 'fos_user_security_login']);
        }

        return $menu;
    }
}
