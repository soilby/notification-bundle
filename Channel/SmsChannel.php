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
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $sender = 'sender';


    /**
     * @var GatewayInterface
     */
    protected $gateway;

    public function __construct($gateway)   {
        $this->gateway = $gateway;
    }

    public function putNotification(Agent $subscriber, $message, $options)  {

        if (!array_key_exists('sender', $options))  {
            $options['sender'] = $this->sender;
        }

        return $this->gateway->send($subscriber->getPhoneNumber(), $message, $options);
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }





} 