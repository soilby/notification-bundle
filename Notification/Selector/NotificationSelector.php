<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 3.2.15
 * Time: 18.59
 */

namespace Soil\NotificationBundle\Notification\Selector;


use EasyRdf\Resource;
use Soil\EventProcessorBundle\Processor\EventProcessorInterface;
use Soil\NotificationBundle\Notification\NotificationInterface;

class NotificationSelector {

    protected $notifications = [];

    public function addNotification($notification)  {
        $this->notifications[] = $notification;
    }

    /**
     * @param $notificationType
     * @return NotificationInterface | bool
     */
    public function selectNotification($notificationType)   {

        foreach ($this->notifications as $notification)  {
            if ($notification->support($notificationType)) {
                return $notification;
            }
        }

        return false;
    }
} 