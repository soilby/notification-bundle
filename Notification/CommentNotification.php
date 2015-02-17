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

    public function notify($agent, $params)    {
        $email = $this->fetchEmail($agent);
        var_dump($email);
        echo $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'agent' => $agent
        ]);

    }

}