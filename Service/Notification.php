<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 16.2.15
 * Time: 13.53
 */

namespace Soil\NotificationBundle\Service;


use EasyRdf\Format;
use EasyRdf\Graph;
use EasyRdf\RdfNamespace;
use Monolog\Logger;
use Soil\DiscoverBundle\Entity\Agent;
use Soil\DiscoverBundle\Service\Resolver;
use Soil\DiscoverBundle\Services\Discoverer;
use Soil\NotificationBundle\Notification\Selector\NotificationSelector;
use Soil\NotificationBundle\Service\Exception\NotificationFail;

class Notification {

    /**
     * @var NotificationSelector
     */
    protected $notificationSelector;

    /**
     * @var Logger
     */
    protected $logger;


    /**
     * @var Resolver
     */
    protected $resolver;

    public function __construct($notificationSelector, $resolver)  {
        $this->notificationSelector = $notificationSelector;
        $this->resolver = $resolver;
    }


    public function notify($notificationType, $subscriberAgentURI, $params = [])    {

        $notification = $this->notificationSelector->selectNotification($notificationType);

        if (!$notification) {
            throw new \Exception("Unknown notification type");
        }

        $this->logger->addInfo('Selected notification: ' . get_class($notification));

        $subscriberAgentURI = $subscriberAgentURI . '?token=3f616f0ab945c34c61cdeaf62b4fe0e8';
        $this->logger->addInfo('subscriber (agent): ' . $subscriberAgentURI);

        $subscriberAgent = $this->resolve($subscriberAgentURI, 'Soil\DiscoverBundle\Entity\Agent');

        foreach ($params as $paramName => &$paramValue)    {

            if (is_scalar($paramValue)) { //for logging purpose
                $stringRepresentation = (string)$paramValue;
            }
            else    {
                $stringRepresentation = gettype($paramValue);
            }

            $this->logger->addInfo('param value: ' . $stringRepresentation);

            if ($this->isURI($paramValue))  {
                $this->logger->addInfo('resolving..');
                $paramValue = $this->resolve($paramValue, true);
            }
        }

        return $notification->notify($subscriberAgent, $params);
    }

    protected function isURI($uri)  {
        return is_string($uri) && strpos($uri, 'http') === 0;
    }


    protected function resolve($uri, $expectEntity = null)  {
        try {
            $entity = $this->resolver->getEntityForURI($uri, $expectEntity);
        }
        catch (\Exception $e)   {
            $this->logger->addCritical('Cannot resolve uri into entity ' . $uri);
            $this->logger->addCritical((string) $e);

            throw new NotificationFail('Cannot finish notification because one entity cannot be resolved', $e->getCode(), $e);
        }

        if (!$entity)   {
            $this->logger->addCritical('Cannot resolve uri into entity ' . $uri);
            throw new NotificationFail('Cannot finish notification because one entity cannot be resolved');
        }

        return $entity;
    }



    public function setLogger($logger)  {
        $this->logger = $logger;
    }




}