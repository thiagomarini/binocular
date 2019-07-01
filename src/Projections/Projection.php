<?php

namespace Binocular\Projections;

interface Projection
{
    public function calculateState($rootId): ?array;

    public function getReducers(): array;

    public function getName(): string;
}