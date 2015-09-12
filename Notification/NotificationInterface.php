<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.56
 */

namespace Soil\NotificationBundle\Notification;


use Soil\DiscoverBundle\Entity\Agent;

interface NotificationInterface {

    public function support($type);

    public function notify(Agent $agent, $params);

    public function addChannel($channelName, $channel);

    public function getChannels();
} 