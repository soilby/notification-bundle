<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 10.9.15
 * Time: 8.00
 */

namespace Soil\NotificationBundle\NotificationTest;



use Soil\NotificationBundle\Notification\NotificationInterface;

abstract class AbstractNotificationTest {

    protected $resolver;

    public function __construct($resolver)  {
        $this->resolver = $resolver;
    }


    abstract public function getParams();

    public function support($type)   {
        return false;
    }


} 