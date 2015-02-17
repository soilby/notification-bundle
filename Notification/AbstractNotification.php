<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.36
 */

namespace Soil\NotificationBundle\Notification;


class AbstractNotification {

    /**
     * @var TwigEngine
     */
    protected $templating;

    public function __construct($templating)    {
        $this->templating = $templating;
    }

    protected function fetchEmail($agent) {
        $mailBox = (string)$agent['mbox'];

        if (!$mailBox || strpos($mailBox, 'mailto:') !== 0) {
            throw new \Exception("Mail box isn't available or corrupted");
        }

        return substr($mailBox, 7);
    }
} 