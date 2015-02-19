<?php
namespace Soil\NotificationBundle\Notification;
use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Symfony\Bundle\TwigBundle\TwigEngine;


/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 9.36
 */

class CommentNotification extends AbstractNotification implements NotificationInterface {

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

    public function notify($subscriber, $params)    {
        $email = $subscriber->mbox;

        $message = $this->templating->render('SoilNotificationBundle:notification:comment_email.html.twig', [
            'subscriber' => $subscriber,
            'author' => $params['author'],
            'comment' => $params['comment'],
            'entity' => $params['entity']

        ]);

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
                    $writer->text($message);
                $writer->endElement();
            $writer->endElement();
        $writer->endDocument();

        $s = $writer->outputMemory(true);


        $request = new Request('POST', '/api/send-mail', 'talaka.by.local');
        $request->setContent($s);

        $response = new Response();
        $this->buzz->send($request, $response);

        echo $response->getContent();


    }

}