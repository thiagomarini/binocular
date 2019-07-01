<?php

namespace Tests\Unit;

use Binocular\Events\Event;
use Binocular\Projections\BaseProjection;

class TestProjection extends BaseProjection
{
    public function getReducers(): array
    {
        return [
            'something_happened'    => [
                1 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    return array_merge($currentState, $eventPayload);
                }
            ],
            'something_else_happened'    => [
                1 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    return array_merge($currentState, $eventPayload);
                }
            ],
        ];
    }

    public function getName(): string
    {
        return 'test_projection';
    }
}