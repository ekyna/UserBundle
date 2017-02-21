<?php

namespace Ekyna\Bundle\UserBundle;

use Ekyna\Bundle\ResourceBundle\AbstractBundle;
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
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelInterfaces()
    {
        return [
            'Ekyna\Bundle\UserBundle\Model\UserInterface' => 'ekyna_user.user.class',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
