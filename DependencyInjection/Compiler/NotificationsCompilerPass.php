<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 3.2.15
 * Time: 18.47
 */

namespace Soil\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationsCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('soil_notification.notification_selector')) {
            return;
        }

        $definition = $container->getDefinition(
            'soil_notification.notification_selector'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'soil_notification'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addNotification',
                array(new Reference($id))
            );
        }




        $definition = $container->getDefinition(
            'soil_notification.controller.notification_test_controller'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'soil_notification_test'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addNotificationTest',
                array(new Reference($id))
            );
        }
    }
} 