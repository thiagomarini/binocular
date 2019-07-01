<?php

namespace Tests\Unit;

use Binocular\Events\EventRepository;
use Binocular\Events\InMemoryEventRepository;
use Tests\BaseEventRepositoryTest;

class InMemoryEventRepositoryTest extends BaseEventRepositoryTest
{
    protected function getRepository(): EventRepository
    {
        return new InMemoryEventRepository;
    }
}