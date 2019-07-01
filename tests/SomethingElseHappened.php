<?php

namespace Tests;

use Binocular\Events\Action;
use Binocular\Events\BaseEvent;

class SomethingElseHappened extends BaseEvent
{
    public function setDate(\DateTime $date)
    {
        $this->createdAt = $date;
    }

    protected function loadActions()
    {
        $this->actions = [
            new Action('something_else_happened', 1),
        ];
    }
}