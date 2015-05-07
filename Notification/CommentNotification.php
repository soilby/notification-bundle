<?php
namespace Soil\NotificationBundle\Notification;
use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Soil\DiscoverBundle\Entity\Agent;
use Soil\NotificationBundle\Channel\ChannelInterface;
use Soil\NotificationBundle\Channel\EmailChannel;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\TwigBundle\TwigEngine;
use \ForceUTF8\Encoding;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.36
 */

class CommentNotification extends AbstractNotification implements NotificationInterface {


    public function support($type)   {
        return $type === 'CommentNotification';
    }

    public function notify(Agent $subscriber, $params)    {

        $this->logger->addInfo('Process comment notification for mailbox ' . $subscriber->displayName);

        $message = $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'subscriber' => $subscriber,
            'author' => $params['author'],
            'comment' => $params['comment'],
            'entity' => $params['entity']
        ]);


        $this->logger->addAlert($subscriber->displayName);

        $this->logger->addAlert($message);

        foreach ($this->channels as $channelName => $channel) {
            $result = $channel->putNotification($subscriber, $message, [
                'subject' => 'New Comment'
            ]);

            if (!$result)   {
                $this->logger->addError('Send notification failed via ' . $channelName);
            }
        }


    }


}