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

        $host = array_key_exists('host', $params) ? $params['host'] : 'default';

        $locale = $subscriber->getLocale();
        $locale = 'ru';
        $template = 'SoilNotificationBundle:new-idea:' . $host . '.' . $locale . '.html.twig';

        try {
            $message = $this->templating->render($template, [
                'subscriber' => $subscriber,
            ]);
        }
        catch(\Exception $e)    {
            $this->logger->addError((string) $e);
            $this->logger->addError('Problem with rendering template ' . $template . '. Try default..');


            $template = 'SoilNotificationBundle:new-idea:default.' . $locale . '.html.twig';
            $message = $this->templating->render($template, [
                'subscriber' => $subscriber,
            ]);
        }


        switch ($host)  {
            case 'cpumoscow.ru';
            case 'www.saratovidea.ru';
                $topic = '5 вещей, которые нужно знать автору идеи';
                break;

            default:
                $topic = '5 вещей, которые нужно знать автору идеи на Talaka.by';
                break;
        }

        $result = $this->channels['email']->putNotification($subscriber, $message, [
            'subject' => $host
        ]);

        $this->logger->addInfo('Mail Channel answer:');
        $this->logger->addInfo(json_encode($result));

    }




} 