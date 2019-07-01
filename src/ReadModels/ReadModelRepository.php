<?php

namespace Binocular\ReadModels;

interface ReadModelRepository
{
    public function get($rootId): ?array;

    public function store($rootId, array $newState);
}