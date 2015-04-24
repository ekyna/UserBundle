<?php

namespace Ekyna\Bundle\UserBundle;

use Ekyna\Bundle\CoreBundle\AbstractBundle;
use Ekyna\Bundle\UserBundle\DependencyInjection\Compiler\ExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ekyna\Bundle\UserBundle\DependencyInjection\Compiler\AdminMenuPass;

/**
 * Class EkynaUserBundle
 * @package Ekyna\Bundle\UserBundle
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AdminMenuPass());
        $container->addCompilerPass(new ExtensionPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelInterfaces()
    {
        return array(
            'Ekyna\Bundle\UserBundle\Model\UserInterface' => 'ekyna_user.user.class',
            'Ekyna\Bundle\UserBundle\Model\AddressInterface' => 'ekyna_user.address.class',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
