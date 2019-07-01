<?php

namespace Binocular\Events;

use Binocular\Exceptions\ActionVersionNotFoundException;
use DateTime;

abstract class BaseEvent implements Event
{
    /**
     * @var int
     */
    protected $defaultActionVersion = 1;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var int
     */
    protected $actionVersion;

    /**
     * @var mixed
     */
    protected $rootId;

    /**
     * @var array
     */
    protected $actions;

    /**
     * @var DateTime
     */
    protected $createdAt;

    public function __construct($rootId, array $payload = [], int $actionVersion = null)
    {
        $this->rootId = $rootId;
        $this->payload = $payload;
        $this->actionVersion = $actionVersion ?? $this->defaultActionVersion;
        $this->createdAt = new DateTime;

        $this->loadActions();
    }

    public function getRootId()
    {
        return $this->rootId;
    }

    public function getSelectedAction(): Action
    {
        foreach ($this->getActions() as $action) {
            if ($action->getVersion() === $this->actionVersion) {
                return $action;
            }
        }

        throw new ActionVersionNotFoundException('Make sure you have an action matching the ACTIVE_VERSION constant');
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getActionVersion(): int
    {
        return $this->defaultActionVersion;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getSnapshotProjectionName(): ?string
    {
        return null;
    }

    public function equals(Event $event): bool
    {
        return $this->getActionVersion() === $event->getActionVersion()
            && $this->getSnapshotProjectionName() === $event->getSnapshotProjectionName()
            && $this->getRootId() === $event->getRootId()
            && $this->getPayload() === $event->getPayload()
            && $this->getCreatedAt()->format(DATE_RFC3339_EXTENDED) === $event->getCreatedAt()->format(DATE_RFC3339_EXTENDED);
    }

    abstract protected function loadActions();
}