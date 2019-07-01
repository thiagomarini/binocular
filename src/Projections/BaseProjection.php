<?php

namespace Binocular\Projections;

use Binocular\Events\Action;
use Binocular\Events\Event;
use Binocular\Events\EventRepository;

abstract class BaseProjection implements Projection
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function calculateState($rootId): ?array
    {
        $events = $this->eventRepository->all($rootId);

        $reducers = $this->getReducers();

        $currentState = [];

        foreach ($events as $event) {
            $action = $event->getSelectedAction();

            $reducer = $this->getMappedReducerFromAction($reducers, $action);

            $currentState = $this->reduce($reducer, $currentState, $event);
        }

        return $currentState;
    }

    abstract public function getName(): string;

    abstract public function getReducers(): array;

    protected function reduce(callable $reducer, array $currentState, ?Event $event): array
    {
        return $reducer(is_null($currentState) ? [] : $currentState, $event);
    }

    protected function getMappedReducerFromAction(array $reducers, Action $action): callable
    {
        if (!isset($reducers[$action->getName()][$action->getVersion()])) {
            throw new \RuntimeException(
                sprintf('Action %s not found for version %s', $action->getName(), $action->getVersion())
            );
        }

        $reducer = $reducers[$action->getName()][$action->getVersion()];

        if (!is_callable($reducer)) {
            throw new \RuntimeException(
                sprintf('Action %s version %s is not a callable', $action->getName(), $action->getVersion())
            );
        }

        return $reducer;
    }
}