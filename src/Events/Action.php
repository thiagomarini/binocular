<?php

namespace Binocular\Events;

final class Action
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $version;

    public function __construct(string $name, int $version)
    {
        $this->name = $name;
        $this->version = $version;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name'    => $this->getName(),
            'version' => $this->getVersion(),
        ];
    }
}