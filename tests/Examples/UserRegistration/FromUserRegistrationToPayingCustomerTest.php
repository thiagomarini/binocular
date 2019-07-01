<?php

namespace Tests\Examples\UserRegistration;

use PHPUnit\Framework\TestCase;
use Binocular\Events\InMemoryEventRepository;
use Binocular\ReadModels\InMemoryReadModelRepository;
use Tests\Examples\UserRegistration\Events\UserMadePayment;
use Tests\Examples\UserRegistration\Events\UserNameWasUpdated;
use Tests\Examples\UserRegistration\Events\UserSignedUp;

class FromUserRegistrationToPayingCustomerTest extends TestCase
{
    const ROOT_ID = 'foo';

    public function test_whole_flow()
    {
        $eventRepository = new InMemoryEventRepository;

        $readModelRepository = new InMemoryReadModelRepository;

        $onboardingProjection = new OnboardingProjection($eventRepository);

        /**
         * First event
         */
        $eventRepository->store(
            new UserSignedUp(self::ROOT_ID, ['name' => 'John'])
        );

        // update projection state
        $newState = $onboardingProjection->calculateState(self::ROOT_ID);
        $readModelRepository->store(self::ROOT_ID, $newState);

        $expected = [
            'name'              => 'John',
            'onboarding_status' => 'signed_up'
        ];
        $this->assertEquals($expected, $newState);

        /**
         * Second event, update name with version 1 action
         */
        $eventRepository->store(
            new UserNameWasUpdated(self::ROOT_ID, ['name' => 'John Smith'], 1)
        );

        // update projection state
        $newState = $onboardingProjection->calculateState(self::ROOT_ID);
        $readModelRepository->store(self::ROOT_ID, $newState);

        $expected = [
            'name'              => 'John Smith',
            'onboarding_status' => 'signed_up'
        ];
        $this->assertEquals($expected, $newState);

        /**
         * Third event, update name again but this time with version 2 of the action
         */
        $eventRepository->store(
            new UserNameWasUpdated(self::ROOT_ID, ['name' => 'John Glen Smith'], 2)
        );

        // update projection state
        $newState = $onboardingProjection->calculateState(self::ROOT_ID);
        $readModelRepository->store(self::ROOT_ID, $newState);

        $expected = [
            'name'         => 'John Glen Smith',
            'first_letter' => 'J',
            'onboarding_status' => 'signed_up'
        ];
        $this->assertEquals($expected, $newState);

        /**
         * Final event, payment was made, onboarding is completed
         */
        $eventRepository->store(
            new UserMadePayment(self::ROOT_ID, ['amount' => 10.5])
        );

        // update projection state
        $newState = $onboardingProjection->calculateState(self::ROOT_ID);
        $readModelRepository->store(self::ROOT_ID, $newState);

        $expected = [
            'name'              => 'John Glen Smith',
            'revenue_to_date'   => 10.5,
            'onboarding_status' => 'completed',
            'first_letter'      => 'J'
        ];
        $this->assertEquals($expected, $newState);
    }
}