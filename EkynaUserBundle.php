<?php

namespace Ekyna\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ekyna\Bundle\UserBundle\DependencyInjection\Compiler\AdminMenuPass;

class EkynaUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AdminMenuPass());
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
