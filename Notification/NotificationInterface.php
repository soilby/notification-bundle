<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.56
 */

namespace Soil\NotificationBundle\Notification;


interface NotificationInterface {

    public function support($type);

    public function notify($agent, $params);
} 