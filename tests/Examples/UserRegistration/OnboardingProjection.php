<?php

namespace Tests\Examples\UserRegistration;

use Binocular\Events\Event;
use Binocular\Projections\BaseProjection;

class OnboardingProjection extends BaseProjection
{
    public function getReducers(): array
    {
        return [
            'signed_up'    => [
                1 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    $newState = $eventPayload;
                    $newState['onboarding_status'] = 'signed_up';

                    return $newState;
                }
            ],
            'name_updated' => [
                1 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    $newState = $currentState;
                    $newState['name'] = $eventPayload['name'];

                    return $newState;
                },
                2 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    $newState = $currentState;
                    $newState['name'] = $eventPayload['name'];
                    $newState['first_letter'] = $newState['name'][0];

                    return $newState;
                }
            ],
            'payment_made' => [
                1 => function (array $currentState, Event $event): ?array {
                    // calculate new state
                    $eventPayload = $event->getPayload();
                    $newState = $currentState;
                    $newState['onboarding_status'] = 'completed';
                    $newState['revenue_to_date'] = $eventPayload['amount'];

                    return $newState;
                }
            ],
        ];
    }

    public function getName(): string
    {
        return 'onboarding_projection';
    }
}