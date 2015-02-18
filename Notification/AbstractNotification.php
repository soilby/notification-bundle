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

}