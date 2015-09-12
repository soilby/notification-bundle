<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 8.00
 */

namespace Soil\NotificationBundle\NotificationTest;



use Soil\NotificationBundle\Notification\NotificationInterface;

abstract class AbstractNotificationTest {


    protected $supportedChannels = [];

    /**
     * @var NotificationInterface
     */
    protected $notification;

    protected $templating;
    protected $testChannel;

    public function __construct($templating, $testChannel) {
        $this->templating = $templating;
        $this->testChannel = $testChannel;
    }

    protected function configureNotification()  {
        foreach ($this->supportedChannels as $channel)  {
            $this->notification->addChannel($channel, $this->testChannel);
        }
    }

    public function output()   {
        $this->configureNotification();

        $this->notification->notify($agent, $params);
        $params = $this->getParams();

    }


    abstract protected function getParams();

    /**
     * @param NotificationInterface $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }


} 