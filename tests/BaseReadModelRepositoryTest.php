<?php

namespace Tests;

use Binocular\ReadModels\ReadModelRepository;
use PHPUnit\Framework\TestCase;

abstract class BaseReadModelRepositoryTest extends TestCase
{
    const ROOT_ID = 'foo';

    public function test_new_read_model_state_can_be_stored()
    {
        $readModelRepository = $this->getRepository();

        $readModelRepository->store(self::ROOT_ID, ['foo' => 'bar']);

        $state = $readModelRepository->get(self::ROOT_ID);

        $this->assertEquals(['foo' => 'bar'], $state);
    }

    public function test_new_read_model_state_are_grouped_by_root_id()
    {
        $readModelRepository = $this->getRepository();

        $readModelRepository->store(self::ROOT_ID, ['foo' => 'bar']);
        $readModelRepository->store('bar', ['bar' => 'baz']);

        $state = $readModelRepository->get(self::ROOT_ID);

        $this->assertEquals(['foo' => 'bar'], $state);
    }

    public function test_null_is_returned_if_state_not_found_for_given_root_id()
    {
        $readModelRepository = $this->getRepository();

        $readModelRepository->store('bar', ['bar' => 'baz']);

        $state = $readModelRepository->get(self::ROOT_ID);

        $this->assertNull($state);
    }

    abstract protected function getRepository(): ReadModelRepository;
}