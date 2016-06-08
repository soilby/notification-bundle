<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 7.45
 */

namespace Soil\NotificationBundle\NotificationTest;

use EasyRdf\Literal;
use Soil\NotificationBundle\Notification\NewIdeaNotification;
use Soil\NotificationBundle\Notification\NotificationInterface;

class CampaignCompleteNotificationTest extends AbstractNotificationTest {

    protected $supportedChannels = ['email'];


    public function support($type)   {
        return $type === 'CampaignCompleteNotification';
    }

    public function getParams()  {
        return [
            'entity' => 'http://test.api.talaka.by/talakosht/campaign/5757273093f81d2f0b8b7595',
            'paymentLink' => new Literal('http://stage.talaka.by/projects/1289/fund/pay/5757273093f81d2f0b8b759b'),
            'promiseURI' => 'http://test.api.talaka.by/promise/5757273093f81d2f0b8b759b',
            'promiseSum' => 100000,
            'promiseDate' => '01.01.2016'
        ];
    }

}