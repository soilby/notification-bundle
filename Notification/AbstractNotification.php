<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.36
 */

namespace Soil\NotificationBundle\Notification;


use Soil\DiscoverBundle\Entity\Agent;
use Soil\NotificationBundle\Channel\ChannelInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bridge\Twig\TwigEngine;

class AbstractNotification {

    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var ChannelInterface[]
     */
    protected $channels;

    /**
     * @var Logger
     */
    protected $logger;


    public function __construct($templating)    {
        $this->templating = $templating;
    }


    public function addChannel($channelName, $channel)  {
        $this->channels[$channelName] = $channel;
    }


    public function setLogger($logger)  {
        $this->logger = $logger;
    }


    public function broadcast(Agent $subscriber, $message, $options)    {
        foreach ($this->channels as $channelName => $channel) {
            $result = $channel->putNotification($subscriber, $message, $options);

            if (!$result)   {
                $this->logger->addError('Send notification failed via ' . $channelName);
            }
        }
    }


}