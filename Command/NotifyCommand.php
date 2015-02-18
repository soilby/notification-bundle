<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 26.1.15
 * Time: 20.07
 */

namespace Soil\NotificationBundle\Command;

use EasyRdf\Http\Exception;
use Soil\NotificationBundle\Service\Notification;
use Soil\RDFProcessorBundle\Service\EndpointClient;
use Soil\RDFProcessorBundle\Service\RDFProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class NotifyCommand extends Command  {

    /**
     * @var Notification
     */
    protected $notification;


    public function __construct($notification)   {
        parent::__construct();

        $this->notification = $notification;
    }


    protected function configure()
    {
        $this
            ->setName('soil:notify')
            ->setDescription('Notify user')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'Notification type', 'CommentNotification'
            )
            ->addArgument(
                'agent',
                InputArgument::OPTIONAL,
                'Notification type', 'http://talaka.by.local/user/8118'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $this->notification->notify($input->getArgument('type'), $input->getArgument('agent'), [
                'entity' => 'http://talaka.by.local/incubator/idea/2',
                'target' => 'http://comments.talaka.by.local/comment/54e0e0731388418e2f8cdd51',
                'author' => 'http://talaka.by.local/user/8119'
            ]);

        }
        catch(Exception $e) {
            echo $e->getBody();
        }
    }
} 