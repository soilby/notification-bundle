<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 16.2.15
 * Time: 13.53
 */

namespace Soil\NotificationBundle\Service;


use EasyRdf\Format;
use EasyRdf\Graph;
use EasyRdf\RdfNamespace;
use Soil\DiscoverBundle\Entity\Agent;
use Soil\DiscoverBundle\Service\Resolver;
use Soil\DiscoverBundle\Services\Discoverer;
use Soil\NotificationBundle\Notification\Selector\NotificationSelector;

class Notification {

    /**
     * @var NotificationSelector
     */
    protected $notificationSelector;

    /**
     * @var Resolver
     */
    protected $resolver;

    public function __construct($notificationSelector, $resolver)  {
        $this->notificationSelector = $notificationSelector;
        $this->resolver = $resolver;
    }


    public function notify($notificationType, $subscriberAgentURI, $params = [])    {
        $notification = $this->notificationSelector->selectNotification($notificationType);
        if (!$notification) {
            throw new \Exception("Unknow notification type");
        }

        $subscriberAgent = $this->resolver->getEntityForURI($subscriberAgentURI, 'Soil\DiscoverBundle\Entity\Agent');

        foreach ($params as &$paramValue)    {
            $paramValue = $this->resolver->getEntityForURI($paramValue, true);
        }

        $notification->notify($subscriberAgent, $params);
    }






}