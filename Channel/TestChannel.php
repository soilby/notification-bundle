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

use Symfony\Bridge\Monolog\Logger;


class TestChannel implements ChannelInterface {


    /**
     * @var Logger
     */
    protected $logger;

    protected $tmpStorage = [];


    public function __construct()   {
    }

    public function setLogger($logger)  {
        $this->logger = $logger;
    }



    public function putNotification(Agent $subscriber, $message, $options) {

        echo '<hr>';
        echo '<b>Subscriber</b><br />';
        echo '<pre>';
        echo 'email:';
        var_dump($subscriber->getEmail());
        echo 'phone:';
        var_dump($subscriber->getPhone());

        echo 'display name:';
        var_dump($subscriber->getDisplayName());
        echo 'avatar:';
        var_dump($subscriber->getImg());
        echo 'locale:';
        var_dump($subscriber->getLocale());
        echo '</pre>';

        echo '<hr>';


        var_dump($message);

        $this->tmpStorage[$subscriber->getOrigin()] = [
            'subscriber' => $subscriber,
            'message' => $message,
            'options' => $options
        ];
    }

    /**
     * @return array
     */
    public function getTmpStorage()
    {
        return $this->tmpStorage;
    }


} 