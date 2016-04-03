<?php

namespace InfrastructureBundle\Adapter;

use Application\Contract\EventListener;
use Application\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventListener
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::PRE_COMMAND => 'onDomainApplicationEvent',
            Events::POST_COMMAND => 'onDomainApplicationEvent',
            Events::EXCEPTION => 'onDomainApplicationEvent'
        );
    }

    public function onDomainApplicationEvent($event)
    {
        $wrapped = new DomainApplicationWrappedEvent($event);

        $this->eventDispatcher->dispatch('domain-application-event', $wrapped);
    }
}
