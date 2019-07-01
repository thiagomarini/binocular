<?php

namespace Binocular\Events;

abstract class BaseSnapshotEvent extends BaseEvent
{
    /**
     * @var string
     */
    protected $snapshotProjectionName;

    /**
     * @var int
     */
    protected $defaultActionVersion = -1;

    public function __construct($rootId, string $snapshotProjectionName, array $payload)
    {
        parent::__construct($rootId, $payload);

        $this->snapshotProjectionName = $snapshotProjectionName;
    }

    public function getSelectedAction(): Action
    {
        return $this->actions[0];
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    protected function loadActions()
    {
        $this->actions = [
            new Action('snapshot', -1)
        ];
    }

    public function getActionVersion(): int
    {
        return $this->defaultActionVersion;
    }

    public function getSnapshotProjectionName(): ?string
    {
        return $this->snapshotProjectionName;
    }
}