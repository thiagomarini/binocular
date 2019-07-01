<?php

namespace Tests\Examples\UserRegistration\Events;

use Binocular\Events\Action;
use Binocular\Events\BaseEvent;

class UserSignedUp extends BaseEvent
{
    protected function loadActions()
    {
        $this->actions = [
            new Action('signed_up', 1),
        ];
    }
}