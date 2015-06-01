<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 31.5.15
 * Time: 10.36
 */

namespace Soil\NotificationBundle\Channel;

use Monolog\Logger;
use Soil\DiscoverBundle\Entity\Agent;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\QuantityLimitedShowStrategy;
use Soil\OnSiteNotificationBundle\Service\NotificationManager;

class OnSiteChannel implements ChannelInterface {

    /**
     * @var NotificationManager
     */
    protected $notificationManager;

    /**
     * @var Logger
     */
    protected $logger;



    public function __construct($notificationManager)   {
        $this->notificationManager = $notificationManager;
    }

    public function putNotification(Agent $subscriber, $message, $options)  {

        $notification = $this->notificationManager->factory();

        $notification->setAgentURI($subscriber->getOrigin());

        $notification->setMessage($message);

        if (array_key_exists('relatedLink', $options))  {
            $notification->setRelatedLink($options['relatedLink']);
        }

        if (array_key_exists('showStrategy', $options))  {
            $notification->setShowStrategy($options['showStrategy']);
        }

        if (array_key_exists('action', $options))  {
            $notification->setAction($options['action']);
        }

        if (array_key_exists('promiseURI', $options))  {
            $notification->setPromiseURI($options['promiseURI']);
        }

        $this->notificationManager->persist($notification);
        $this->notificationManager->flush();


        return true;
    }

    public function setLogger($logger)  {
        $this->logger = $logger;
    }


} 