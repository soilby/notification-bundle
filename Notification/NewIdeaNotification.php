<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 5.5.15
 * Time: 20.52
 */

namespace Soil\NotificationBundle\Notification;

use Soil\DiscoverBundle\Entity\Agent;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\OnEnterShowStrategy;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\QuantityLimitedShowStrategy;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\QuantityLimitWithTermBetween;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\ShowStrategyInterface;

class NewIdeaNotification extends AbstractNotification implements NotificationInterface {


    public function support($type)   {
        return $type === 'NewIdeaNotification';
    }

    public function getTestParams() {

    }

    public function notify(Agent $subscriber, $params)
    {
        $this->logger->addInfo('New Idea notify...');

        $email = $subscriber->getEmail();

        $this->logger->addInfo('Process new idea notification for mailbox ' . $subscriber->getDisplayName());

        $locale = $subscriber->getLocale();
        $locale = 'ru';
        $template = 'SoilNotificationBundle:new-idea:new-idea.' . $locale . '.html.twig';

        $message = $this->templating->render($template, [
            'subscriber' => $subscriber,
        ]);

        $result = $this->channels['email']->putNotification($subscriber, $message, [
            'subject' => '5 вещей, которые нужно знать автору идеи на Talaka.by',
        ]);

        $this->logger->addInfo('Mail Channel answer:');
        $this->logger->addInfo(json_encode($result));

    }




} 