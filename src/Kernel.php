<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
#use Symfony\Component\DependencyInjection\ContainerBuilder;  // ← Correct import

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #protected function build(ContainerBuilder $container): void
    #{
    #    $container->setParameter('app.maintenance_flag', 'off');
    #}
}
