<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 7.5.15
 * Time: 8.19
 */

namespace Soil\NotificationBundle\Channel;

use Soil\DiscoverBundle\Entity\Agent;

use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;

use Soil\SmserBundle\Gateway\GatewayInterface;
use Symfony\Bridge\Monolog\Logger;


class SmsChannel implements ChannelInterface {


    /**
     * @var GatewayInterface
     */
    protected $gateway;

    public function __construct($gateway)   {
        $this->gateway = $gateway;
    }

    public function putNotification(Agent $subscriber, $message, $options)  {

        return $this->gateway->send($subscriber->getPhoneNumber(), $message, []);
    }


} 