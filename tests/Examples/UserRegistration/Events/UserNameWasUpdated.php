<?php

namespace Tests\Examples\UserRegistration\Events;

use Binocular\Events\Action;
use Binocular\Events\BaseEvent;

class UserNameWasUpdated extends BaseEvent
{
    protected function loadActions()
    {
        $this->actions = [
            new Action('name_updated', 1),
            new Action('name_updated', 2),
        ];
    }
}