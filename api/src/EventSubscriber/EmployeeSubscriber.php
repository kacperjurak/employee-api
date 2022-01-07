<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Employee;
use App\Message\ReqresNotification;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

final class EmployeeSubscriber implements EventSubscriberInterface
{
    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(private MessageBusInterface $messageBus)
    {
    }


    #[ArrayShape([KernelEvents::VIEW => 'array'])] public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendNotification', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function sendNotification(ViewEvent $event): void
    {
        /** @var $employee $employee */
        $employee = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$employee instanceof Employee || Request::METHOD_POST !== $method) {
            return;
        }

        $this->messageBus->dispatch(new ReqresNotification($employee->getId(), $employee->getLogin(), $employee->getPosition()));
    }
}
