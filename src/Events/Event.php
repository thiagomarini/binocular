<?php

namespace Binocular\Events;

interface Event
{
    public function getSelectedAction(): Action;

    public function getActions(): array;

    public function getPayload(): array;

    public function getActionVersion(): int;

    public function getRootId();

    public function getSnapshotProjectionName(): ?string;

    public function getCreatedAt(): \DateTime;

    public function setCreatedAt(\DateTime $createdAt);

    public function equals(Event $event): bool;
}