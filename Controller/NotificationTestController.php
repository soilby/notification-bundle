<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 7.45
 */

namespace Soil\NotificationBundle\Controller;


use Soil\NotificationBundle\Notification\Selector\NotificationSelectorTest;
use Soil\NotificationBundle\NotificationTest\AbstractNotificationTest;
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

    /**
     * @var AbstractNotificationTest[]
     */
    protected $notificationTestSet = [];

    public function addNotificationTest($test)  {
        $this->notificationTestSet[] = $test;
    }

    protected function selectTest($notificationType)    {
        foreach ($this->notificationTestSet as $test)   {
            if ($test->support($notificationType))  {
                return $test;
            }
        }

        return null;
    }

    public function __construct($notificationService, $testSelector) {
        $this->notificationService = $notificationService;

        $this->testSelector = $testSelector;

        $this->notificationService->setNotificationSelector($testSelector);
    }

    public function outputAction($notificationType, $flag)   {


        $test = $this->selectTest($notificationType);
        if ($test)  {
            $params = $test->getParams();
        }
        else    {
            $params = [];
        }

        ob_start();
        $ret = $this->notificationService->notify(
            $notificationType,
            'http://dev.talaka.by/user/941',
            $params
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