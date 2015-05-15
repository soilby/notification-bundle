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
        $this->logger->addInfo('Campaign complete notify...');

        $email = $subscriber->getEmail();

        $this->logger->addInfo('Process campaign complete notification for mailbox ' . $subscriber->getDisplayName());

        $entity = $params['entity'];

//        var_dump($entity->getImage()->getThumbnail());exit();


        $originURL = $entity->getOrigin();

        $baseURL = substr($originURL, 0, strpos($originURL, '/', 7)); //next slash after https://

        $message = $this->templating->render('SoilNotificationBundle:notification:campaign_complete_email.html.twig', [
            'subscriber' => $subscriber,
            'entity' => $entity,
            'promiseSum' => $params['promiseSum'],
            'promiseDate' => $params['promiseDate'],
            'baseURL' => $baseURL
        ]);

        $this->broadcast($subscriber, $message, [
            'subject' => 'Оплата поддержки проекта' . ' ' . $entity->name,
        ]);


    }




} 