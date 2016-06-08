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


class EmailChannel implements ChannelInterface {


    /**
     * @var Curl
     */
    protected $buzz;

    /**
     * @var Logger
     */
    protected $logger;


    /**
     * @var string
     */
    protected $mailGateway;

    public function __construct($mailGateway)   {
        $this->mailGateway = $mailGateway;
    }


    public function setBuzz($buzz)  {
        $this->buzz = $buzz;
        $this->buzz->setTimeout(10000);
    }



    public function setLogger($logger)  {
        $this->logger = $logger;
    }



    public function putNotification(Agent $subscriber, $message, $options) {
        $email = $subscriber->getEmail();
        $encodedMessage = base64_encode($message);

        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0','UTF-8');
        $writer->startElement('envelope');

        $writer->startElement('subject');
            $writer->text($options['subject']);
        $writer->endElement();

        if (array_key_exists('host', $options)) {
            switch ($options['host'])   {
                case 'www.saratovidea.ru':
                    $from = 'no-reply@saratovidea.ru';
                    break;
                case 'cpumoscow.ru':
                    $from = 'no-reply@cpumoscow.ru';
                    break;
                case 'www.talaka.by';
                default;
                    $from = 'poshta@talaka.by';
                    break;
            }

            $writer->startElement('from');
                $writer->text($from);
            $writer->endElement();
        }

        $writer->startElement('recipient');
        $writer->startElement('mbox');
        $writer->text($email);
        $writer->endElement();
        $writer->endElement();

        $writer->startElement('message');
        $writer->text($encodedMessage);
        $writer->endElement();

        $writer->endElement();
        $writer->endDocument();

        $s = $writer->outputMemory(true);
//var_dump($this->mailGateway);
//echo $s;
//        exit;
        

        $request = new Request('POST', $this->mailGateway);
//        $request = new Request('POST', '/send', 'sendmail.talaka.by');
        $request->setContent($s);

        $response = new Response();
        try {
            $this->buzz->send($request, $response);

//            $this->logger->addInfo('sleep 3 sec');
//            sleep(3);

            $this->logger->addInfo('mail server answer');
            $this->logger->addInfo($response->getContent());

            return true;
        }
        catch(\Exception $e)    {
            $this->logger->addEmergency('Send mail error');
            $this->logger->addEmergency((string) $e);

            return false;
        }
    }
} 