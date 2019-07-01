<?php

namespace Tests;

use Binocular\Events\EventRepository;
use PHPUnit\Framework\TestCase;

abstract class BaseEventRepositoryTest extends TestCase
{
    const ROOT_ID = 'foo';

    public function test_events_can_be_stored()
    {
        $eventRepository = $this->getRepository();

        $event = new SomethingHappened(self::ROOT_ID, []);

        $eventRepository->store($event);

        $events = $eventRepository->all(self::ROOT_ID);

        $this->assertCount(1, $events);

        $this->assertTrue($event->equals($events[0]));
    }

    public function test_events_fetched()
    {
        $eventRepository = $this->getRepository();

        $event1 = new SomethingHappened(self::ROOT_ID, []);
        $eventRepository->store($event1);

        $event2 = new SomethingHappened(self::ROOT_ID, []);
        $eventRepository->store($event2);

        $events = $eventRepository->all(self::ROOT_ID);

        $this->assertCount(2, $events);
    }

    public function test_events_are_grouped_by_root_id()
    {
        $eventRepository = $this->getRepository();

        $event1 = new SomethingHappened(self::ROOT_ID, []);
        $eventRepository->store($event1);

        $event2 = new SomethingHappened('bar', []);
        $eventRepository->store($event2);

        $events = $eventRepository->all(self::ROOT_ID);

        $this->assertCount(1, $events);
    }

    public function test_events_can_be_selected_from_point_in_time()
    {
        $eventRepository = $this->getRepository();

        $event1 = new SomethingHappened(self::ROOT_ID, []);
        $event1->setDate(new \DateTime('2019-01-01 00:00:00'));
        $eventRepository->store($event1);

        $event2 = new SomethingHappened(self::ROOT_ID, []);
        $event2->setDate(new \DateTime('2019-01-02 00:00:00'));
        $eventRepository->store($event2);

        $event3 = new SomethingHappened(self::ROOT_ID, []);
        $event3->setDate(new \DateTime('2019-01-03 00:00:00'));
        $eventRepository->store($event3);

        $events = $eventRepository->all(self::ROOT_ID, new \DateTime('2019-01-02 00:00:00'));

        $this->assertCount(2, $events);
    }

    public function test_first_snapshot_after_given_point_in_time_can_be_fetched()
    {
        $eventRepository = $this->getRepository();

        $projectionName = 'Foo';

        $event1 = new TestSnapshotEvent(self::ROOT_ID, $projectionName, []);
        $event1->setDate(new \DateTime('2019-01-01 00:00:00'));
        $eventRepository->store($event1);

        $event2 = new SomethingHappened(self::ROOT_ID, []);
        $event2->setDate(new \DateTime('2019-01-02 00:00:00'));
        $eventRepository->store($event2);

        $event3 = new TestSnapshotEvent(self::ROOT_ID, $projectionName, []);
        $event3->setDate(new \DateTime('2019-01-02 00:00:00'));
        $eventRepository->store($event3);

        $snapshot = $eventRepository->getFirstSnapshotAfter(
            self::ROOT_ID,
            $projectionName,
            new \DateTime('2019-01-02 00:00:00')
        );

        $this->assertTrue($event3->equals($snapshot));
    }

    abstract protected function getRepository(): EventRepository;
}