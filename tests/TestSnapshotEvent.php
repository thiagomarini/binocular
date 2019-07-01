<?php

namespace Tests;

use Binocular\Events\BaseSnapshotEvent;

class TestSnapshotEvent extends BaseSnapshotEvent
{
    public function setDate(\DateTime $date)
    {
        $this->createdAt = $date;
    }
}