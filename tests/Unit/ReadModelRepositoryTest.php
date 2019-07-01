<?php

namespace Tests\Unit;

use Binocular\ReadModels\InMemoryReadModelRepository;
use Binocular\ReadModels\ReadModelRepository;
use Tests\BaseReadModelRepositoryTest;

class ReadModelRepositoryTest extends BaseReadModelRepositoryTest
{
    protected function getRepository(): ReadModelRepository
    {
        return new InMemoryReadModelRepository;
    }
}