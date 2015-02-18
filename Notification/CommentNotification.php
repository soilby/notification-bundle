<?php
namespace Soil\NotificationBundle\Notification;
use Symfony\Bundle\TwigBundle\TwigEngine;


/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.36
 */

class CommentNotification extends AbstractNotification {

    public function support($type)   {
        return $type === 'CommentNotification';
    }

    public function notify($subscriber, $params)    {
        $email = $subscriber->mbox;

        echo $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'subscriber' => $subscriber,
            'author' => $params['author'],
            'comment' => $params['target'],
            'entity' => $params['entity']

        ]);

    }

}