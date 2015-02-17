<?php

namespace Soil\NotificationBundle;

use Soil\NotificationBundle\DependencyInjection\Compiler\NotificationsCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SoilNotificationBundle extends Bundle
{

    public function build(ContainerBuilder $container)  {
        $container->addCompilerPass(new NotificationsCompilerPass());
    }
}
