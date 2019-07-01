<?php

namespace Tests\Unit;

use Binocular\Events\EventRepository;
use Binocular\Events\InMemoryEventRepository;
use Binocular\ReadModels\InMemoryReadModelRepository;
use Binocular\ReadModels\ReadModelRepository;
use PHPUnit\Framework\TestCase;
use Tests\SomethingElseHappened;
use Tests\SomethingHappened;

class ProjectionTest extends TestCase
{
    const ROOT_ID = 'foo';

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var ReadModelRepository
     */
    private $readModelRepository;

    /**
     * @var TestProjection
     */
    private $testProjection;

    public function setUp()
    {
        parent::setUp();

        $this->eventRepository = new InMemoryEventRepository;

        $this->readModelRepository = new InMemoryReadModelRepository;

        $this->testProjection = new TestProjection($this->eventRepository);
    }

    public function test_state_calculation()
    {
        $cachedState = ['yes' => 'no'];

        $this->readModelRepository->store(self::ROOT_ID, $cachedState);

        $this->eventRepository->store(
            new SomethingHappened(self::ROOT_ID, ['foo' => 'bar'])
        );

        $this->eventRepository->store(
            new SomethingElseHappened(self::ROOT_ID, ['yes' => 'no'])
        );

        $newState = $this->testProjection->calculateState(self::ROOT_ID);

        $this->assertEquals(['foo' => 'bar', 'yes' => 'no'], $newState);
    }
}