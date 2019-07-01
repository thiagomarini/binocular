<?php

namespace Tests\Examples\UserRegistration\Events;

use Binocular\Events\Action;
use Binocular\Events\BaseEvent;

class UserMadePayment extends BaseEvent
{
    protected function loadActions()
    {
        $this->actions = [
            new Action('payment_made', 1),
        ];
    }
}