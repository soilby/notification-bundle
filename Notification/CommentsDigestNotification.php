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

class CommentsDigestNotification extends AbstractNotification implements NotificationInterface {


    public function support($type)   {
        return $type === 'CommentsDigestNotification';
    }

    public function notify(Agent $subscriber, $params)
    {
        $this->logger->addInfo('Comment digest notify...');

        $email = $subscriber->getEmail();

        $this->logger->addInfo('Process comment digest notification for mailbox ' . $subscriber->getDisplayName());

        $locale = $subscriber->getLocale();
        $locale = 'ru';
        $template = 'SoilNotificationBundle:comments-digest:grouped-comments.' . $locale . '.html.twig';

        $groupedComments = $params['groupedComments'];

        $entitiesHash = [];
        $forMyEntitiesCommentsByEntity = [];
        if (array_key_exists('Soil\CommentsDigestBundle\SubscribersMiner\EntityAuthorsMiner', $groupedComments)) {

            $forMyEntitiesComments = $groupedComments['Soil\CommentsDigestBundle\SubscribersMiner\EntityAuthorsMiner'];
            $forMyEntitiesCommentsByEntity = [];

            foreach ($forMyEntitiesComments as $brief) {
                $entityURI = $brief->getEntity()->getOrigin();

                var_dump($entityURI);
                if (!array_key_exists($entityURI, $forMyEntitiesCommentsByEntity)) {
                    $forMyEntitiesCommentsByEntity[$entityURI] = [];
                    $entitiesHash[$entityURI] = $brief->getEntity();
                }
                $forMyEntitiesCommentsByEntity[$entityURI][] = $brief;
            }

        }

        $myAnswersCommentsByEntity = [];
        if (array_key_exists('Soil\CommentsDigestBundle\SubscribersMiner\AnswersMiner', $groupedComments)) {
            $myAnswersComments = $groupedComments['Soil\CommentsDigestBundle\SubscribersMiner\AnswersMiner'];

            foreach ($myAnswersComments as $brief) {
                $entityURI = $brief->getEntity()->getOrigin();

                var_dump($entityURI);
                if (!array_key_exists($entityURI, $entitiesHash)) {
                    $myAnswersCommentsByEntity[$entityURI] = [];
                    $entitiesHash[$entityURI] = $brief->getEntity();

                    $myAnswersCommentsByEntity[$entityURI][] = $brief;
                }

            }
        }

        $namespaceDecoder = [
            'talidea' => 'Идея'
        ];

        $message = $this->templating->render($template, [
            'namespaceDecoder' => $namespaceDecoder,
            'subscriber' => $subscriber,
            'entitiesHash' => $entitiesHash,
            'comments_for_my_entities' => $forMyEntitiesCommentsByEntity,
            'comments_for_my_comments' => $myAnswersCommentsByEntity,
        ]);

        $result = $this->channels['email']->putNotification($subscriber, $message, [
            'subject' => 'Дайджест комментариев', //Дайджэст каментароў
        ]);

        $this->logger->addInfo('Mail Channel answer:');
        $this->logger->addInfo(json_encode($result));

//        $subscriber->setMbox('talakaby@gmail.com');
        $subscriber->setMbox('grgnvk@gmail.com');
        $result = $this->channels['email']->putNotification($subscriber, $message, [
            'subject' => 'Дайджест комментариев ' . $email,
        ]);

        $this->logger->addInfo('Mail Channel answer:');
        $this->logger->addInfo(json_encode($result));

    }




} 