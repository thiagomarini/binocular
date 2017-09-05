<?php

namespace Binocular;

class Action
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $data;

    public function __construct(string $name, string $version, array $data = [])
    {
        $this->name = $name;
        $this->version = $version;
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getName(): string
    {
        return $this->name;
    }
}