<?php
namespace Soil\NotificationBundle\Notification;
use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Soil\DiscoverBundle\Entity\Agent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\TwigBundle\TwigEngine;


/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.36
 */

class CommentNotification extends AbstractNotification implements NotificationInterface {

    /**
     * @var Logger
     */
    protected $logger;


    /**
     * @var Curl
     */
    protected $buzz;

    public function setBuzz($buzz)  {
        $this->buzz = $buzz;
    }



    public function support($type)   {
        return $type === 'CommentNotification';
    }

    public function notify(Agent $subscriber, $params)    {
        $email = $subscriber->mbox;

        $this->logger->addInfo('Process comment notification for mailbox ' . $subscriber->displayName);

        $message = $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'subscriber' => $subscriber,
            'author' => $params['author'],
            'comment' => $params['comment'],
            'entity' => $params['entity']
        ]);

//        $tmp = $subscriber->displayName;
//        $count = 0;
//        while (mb_detect_encoding($tmp)=="UTF-8")
//        {
//            $tmp = utf8_decode($tmp);
//            $count++;
//        }
//        var_dump(mb_detect_encoding($tmp));
//        var_dump($tmp);
//        var_dump($count);
//        exit();

        $this->logger->addAlert($subscriber->displayName);

        $this->logger->addAlert($message);

        $encodedMessage = base64_encode($message);

        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0','UTF-8');
            $writer->startElement('envelope');

                $writer->startElement('subject');
                    $writer->text('New comment');
                $writer->endElement();

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


        $request = new Request('POST', '/send', 'sendmail.talaka.by');
        $request->setContent($s);

        $response = new Response();
        try {
            $this->buzz->send($request, $response);
        }
        catch(\Exception $e)    {
            $this->logger->addCritical('Send mail error');
            $this->logger->addCritical((string) $e);
        }


        $this->logger->addInfo('mail server answer');
        $this->logger->addInfo($response->getContent());

    }

    public function setLogger($logger)  {
        $this->logger = $logger;
    }

}