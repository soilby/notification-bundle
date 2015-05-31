<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 31.5.15
 * Time: 10.36
 */

namespace Soil\NotificationBundle\Channel;

use Soil\DiscoverBundle\Entity\Agent;
use Soil\OnSiteNotification\Service\NotificationManager;

class OnSiteChannel implements ChannelInterface {

    /**
     * @var NotificationManager
     */
    protected $notificationManager;

    public function __construct($notificationManager)   {
        $this->notificationManager = $notificationManager;
    }

    public function putNotification(Agent $subscriber, $message, $options)  {


    }
} 