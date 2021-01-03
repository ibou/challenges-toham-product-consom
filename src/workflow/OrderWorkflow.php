<?php

namespace App\workflow;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

/**
 * Class OrderWorkflow
 * @package App\workflow
 */
class OrderWorkflow implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * OrderWorkflow constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.order.completed.cancel' => 'onCancel',
            'workflow.order.completed.refuse' => 'onRefuse',
        ];
    }

    public function onCancel(Event $event)
    {
        /** @var Order $order */
        $order = $event->getSubject();

        $order->setCanceledAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }

    public function onRefuse(Event $event)
    {
        /** @var Order $order */
        $order = $event->getSubject();

        $order->setRefusedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }
}
