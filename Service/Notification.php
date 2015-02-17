<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 16.2.15
 * Time: 13.53
 */

namespace Soil\NotificationBundle\Service;


use EasyRdf\Graph;
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


    public function notify($notificationType, $agentURI, $params = [])    {
        var_dump($notificationType);
        $notification = $this->notificationSelector->selectNotification($notificationType);
        if (!$notification) {
            throw new \Exception("Unknow notification type");
        }
        $agentInfo = $this->discover($agentURI);

        foreach ($params as &$paramValue)    {
            $paramValue = $this->discover($paramValue);
        }

        $notification->notify($agentInfo, $params);
    }


    protected function discover($uri)   {
        return $this->resolver->getEntityForURI($uri);
    }





} 