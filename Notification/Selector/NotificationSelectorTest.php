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
use Soil\NotificationBundle\Channel\ChannelInterface;
use Soil\NotificationBundle\Channel\TestChannel;
use Soil\NotificationBundle\Notification\NotificationInterface;

class NotificationSelectorTest extends NotificationSelector {

    /**
     * @var ChannelInterface
     */
    protected $testChannel;

    /**
     * @return TestChannel
     */
    public function getTestChannel()
    {
        return $this->testChannel;
    }

    /**
     * @param ChannelInterface $testChannel
     */
    public function setTestChannel($testChannel)
    {
        $this->testChannel = $testChannel;
    }



    /**
     * @param $notificationType
     * @return NotificationInterface | bool
     */
    public function selectNotification($notificationType)   {
        $notification = parent::selectNotification($notificationType);

        if ($notification) {
            $supportedChannels = array_keys($notification->getChannels());
            foreach ($supportedChannels as $channel) {
                $notification->addChannel($channel, $this->testChannel);
            }
        }

        return $notification;
    }
} 