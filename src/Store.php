<?php

namespace Binocular;

interface Store
{
    public function save(string $id, Action $action);

    public function get(string $id): ?Entity;
}