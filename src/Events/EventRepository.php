<?php

namespace Binocular\Events;

interface EventRepository
{
    public function store(Event $event);

    public function all($rootId, \DateTime $from = null): array;

    public function getFirstSnapshotAfter($rootId, string $snapshotProjectionName, \DateTime $from): ?Event;
}