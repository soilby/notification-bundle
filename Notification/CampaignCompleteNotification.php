<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 5.5.15
 * Time: 20.52
 */

namespace Soil\NotificationBundle\Notification;

use Soil\DiscoverBundle\Entity\Agent;

class CampaignCompleteNotification extends AbstractNotification implements NotificationInterface {


    public function support($type)   {
        return $type === 'CampaignCompleteNotification';
    }

    public function notify(Agent $subscriber, $params)
    {
        exit('NOTIFY');
        $email = $subscriber->mbox;


        $this->logger->addInfo('Process campaign complete notification for mailbox ' . $subscriber->displayName);

        $message = $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'subscriber' => $subscriber,
            'entity' => $params['entity']
        ]);

        var_dump($email);
        var_dump($message);

    }




} 