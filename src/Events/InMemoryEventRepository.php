<?php

namespace Binocular\Events;

class InMemoryEventRepository implements EventRepository
{
    /**
     * @var array
     */
    private $events = [];

    public function store(Event $event)
    {
        if (!isset($this->events[$event->getRootId()])) {
            $this->events[$event->getRootId()] = [];
        }

        $this->events[$event->getRootId()][] = $event;
    }

    /**
     * @return Event[]
     */
    public function all($rootId, \DateTime $from = null): array
    {
        if (!isset($this->events[$rootId])) {
            return [];
        }

        if ($from) {
            return array_filter($this->events[$rootId], function (Event $event) use ($from) {
                return $event->getCreatedAt() <= $from;
            });
        }

        return $this->events[$rootId] ?? [];
    }

    public function getFirstSnapshotAfter($rootId, string $snapshotProjectionName, \DateTime $from): ?Event
    {
        if (!isset($this->events[$rootId])) {
            return null;
        }

        foreach ($this->events[$rootId] as $event) {

            if ($event->getSnapshotProjectionName() == $snapshotProjectionName
                && $event->getCreatedAt() >= $from) {
                return $event;
            }
        }

        return null;
    }
}