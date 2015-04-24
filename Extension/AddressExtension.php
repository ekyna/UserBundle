<?php

namespace Ekyna\Bundle\UserBundle\Extension;

use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class AddressExtension
 * @package Ekyna\Bundle\UserBundle\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressExtension extends AbstractExtension
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->enabled = (bool) $config['account']['address'];
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminShowTab(UserInterface $user)
    {
        if ($this->enabled) {
            $data = [];
            $data['addresses'] = $user->getAddresses();

            return new Admin\ShowTab(
                'ekyna_user.address.label.plural',
                $data,
                'EkynaUserBundle:Admin/Address:user_tab.html.twig'
            );
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountMenuEntries()
    {
        if ($this->enabled) {
            return array('address' => array(
                'label' => 'ekyna_user.account.menu.address',
                'route' => 'ekyna_user_address_list',
                'position' => -1,
            ));
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'address';
    }
}
