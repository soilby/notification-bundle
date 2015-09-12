<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 7.45
 */

namespace Soil\NotificationBundle\Controller;


use Soil\NotificationBundle\Notification\Selector\NotificationSelectorTest;
use Soil\NotificationBundle\Service\Notification;
use Symfony\Component\HttpFoundation\Response;

class NotificationTestController {

    /**
     * @var Notification
     */
    protected $notificationService;

    /**
     * @var NotificationSelectorTest
     */
    protected $testSelector;

    public function __construct($notificationService, $testSelector) {
        $this->notificationService = $notificationService;

        $this->testSelector = $testSelector;

        $this->notificationService->setNotificationSelector($testSelector);
    }

    public function outputAction($notificationType, $flag)   {

        ob_start();
        $ret = $this->notificationService->notify(
            $notificationType,
            'http://dev.talaka.by/user/8118',
            []
        );

        echo 'Notification service return: ';
        var_dump($ret);

        $output = ob_get_clean();

        if ($flag === 'raw') {
            $storage = $this->testSelector->getTestChannel()->getTmpStorage();

            foreach ($storage as $subscriberURI => $info) {
                return new Response($info['message']);
            }
        }


        return new Response($output);
    }

} 