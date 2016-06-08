<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 5.5.15
 * Time: 20.52
 */

namespace Soil\NotificationBundle\Notification;

use EasyRdf\Graph;
use EasyRdf\Resource;
use Soil\DiscoverBundle\Entity\Agent;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\OnEnterShowStrategy;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\QuantityLimitedShowStrategy;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\QuantityLimitWithTermBetween;
use Soil\OnSiteNotificationBundle\Entity\ShowStrategy\ShowStrategyInterface;

class CampaignCompleteNotification extends AbstractNotification implements NotificationInterface {

    protected $paramsTypeVersion = self::GRAPH_V1;

    public function support($type)   {
        return $type === 'CampaignCompleteNotification';
    }

    public function notify(Agent $subscriber, $params)
    {
        $this->logger->addInfo('Campaign complete notify...');

        $this->logger->addInfo('Process campaign complete notification for mailbox ' . $subscriber->getDisplayName());
        
        echo '<pre>';


        /**
         * @var $entity Graph
         */
        $entity = $params['entity'];


        /**
         * @var $campaign Resource[]
         */
        $campaign = $entity->allOfType('schema:Offer');
        if ($campaign)  {
            $campaign = current($campaign);
        }


        $originURL = $campaign->getUri();

        $baseURL = substr($originURL, 0, strpos($originURL, '/', 7)); //next slash after https://

        $locale = $subscriber->getLocale();

        $template = 'SoilNotificationBundle:notification:campaign_complete_email.' . $locale . '.html.twig';

        $message = $this->templating->render($template, [
            'subscriber' => $subscriber,
            'entity' => $campaign,
            'promiseSum' => $params['promiseSum'],
            'promiseDate' => $params['promiseDate'],
            'baseURL' => $baseURL,
            'paymentLink' => $params['paymentLink']
        ]);
        
        $result = $this->channels['email']->putNotification($subscriber, $message, [
            'subject' => 'Оплата поддержки проекта' . ' ' . $campaign->get('schema:name'),
        ]);

        $this->logger->addInfo('Mail Channel answer:');
        $this->logger->addInfo(json_encode($result));


        $template = 'SoilNotificationBundle:notification:campaign_complete.onsite.' . $locale . '.text.twig';

        $this->logger->addInfo('Prepare message for OnSite Notification..');
        $message = $this->templating->render($template, []);

        if (array_key_exists('paymentLink', $params))   {
            $relatedLink = $params['paymentLink'];
            if (is_object($relatedLink))    {
                $relatedLink = (string)$relatedLink;
            }
        }
        else    {
            $relatedLink = null;
        }

        if (array_key_exists('promiseURI', $params))   {
            $promiseURI = $params['promiseURI'];
            if (is_object($promiseURI))    {
                $promiseURI = (string)$promiseURI;
            }

        }
        else    {
            $promiseURI = null;
        }


        $strategy = new QuantityLimitWithTermBetween();
        $strategy->setShowLimit(5);
        $strategy->setTermBetweenShow(24 * 3600);
//        $strategy->setTermBetweenShow(1*60);
        $strategy->setTermControlSide(ShowStrategyInterface::CONTROL_SIDE_CLIENT);

        $this->channels['onsite']->putNotification($subscriber, $message, [
            'action' => 'Оплатить',
            'type' => __CLASS__,
            'relatedLink' => $relatedLink,
            'showStrategy' => $strategy,
            'promiseURI' => $promiseURI
        ]);
    }




} 