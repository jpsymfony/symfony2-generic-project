<?php

namespace App\PortalBundle;
use App\PortalBundle\DependencyInjection\CompilerPass\PaymentCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppPortalBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PaymentCompilerPass());
    }
}
