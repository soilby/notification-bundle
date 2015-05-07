<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 7.5.15
 * Time: 8.21
 */

namespace Soil\NotificationBundle\Channel;

use Soil\DiscoverBundle\Entity\Agent;

interface ChannelInterface {

    public function putNotification(Agent $subscriber, $message, $options);


} 