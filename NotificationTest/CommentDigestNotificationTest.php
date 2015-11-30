<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 7.45
 */

namespace Soil\NotificationBundle\NotificationTest;

use Soil\CommentsDigestBundle\Entity\CommentBrief;
use Soil\NotificationBundle\Notification\NewIdeaNotification;
use Soil\NotificationBundle\Notification\NotificationInterface;

class CommentDigestNotificationTest extends AbstractNotificationTest {

    protected $supportedChannels = ['email'];


    public function support($type)   {
        return $type === 'CommentsDigestNotification';
    }

    public function getParams()  {

        $brief = new CommentBrief();
        $brief->setEntity($this->resolver->getEntityForURI(
            'http://www.talaka.by/projects/53', 'Soil\DiscoverBundle\Entity\TalakaProject'));

        $brief->setAuthor($this->resolver->getEntityForURI(
            'http://dev.talaka.by/user/941', 'Soil\DiscoverBundle\Entity\Agent'));

        $brief->setComment($this->resolver->getEntityForURI(
            'http://profile.talaka.soil.by/comment/55daa83c859cbdab098b458a', 'Soil\DiscoverBundle\Entity\Comment'));


        $brief->setCreationDate(new \DateTime());


        return [
            'groupedComments' => [
                'Soil\CommentsDigestBundle\SubscribersMiner\EntityAuthorsMiner' => [
                    $brief
                ]
            ]
        ];
    }

}