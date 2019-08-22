<?php

namespace Ekyna\Bundle\UserBundle;

use Ekyna\Bundle\ResourceBundle\AbstractBundle;
use Ekyna\Bundle\UserBundle\DependencyInjection\Compiler;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EkynaUserBundle
 * @package Ekyna\Bundle\UserBundle
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUserBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\AdminMenuPass());
        $container->addCompilerPass(new Compiler\SecurityPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 64);
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelInterfaces()
    {
        return [
            UserInterface::class => 'ekyna_user.user.class',
        ];
    }
}
