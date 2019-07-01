<?php

namespace Binocular\ReadModels;

class InMemoryReadModelRepository implements ReadModelRepository
{
    /**
     * @var array
     */
    private $data = [];

    public function get($rootId): ?array
    {
        return $this->data[$rootId] ?? null;
    }

    public function store($rootId, array $newState)
    {
        $this->data[$rootId] = $newState;
    }
}