<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 7.45
 */

namespace Soil\NotificationBundle\NotificationTest;

use Soil\NotificationBundle\Notification\NewIdeaNotification;
use Soil\NotificationBundle\Notification\NotificationInterface;

class NewIdeaTest extends AbstractNotificationTest {

    protected $supportedChannels = ['email'];



    protected function getParams()  {
        return [];
    }

}