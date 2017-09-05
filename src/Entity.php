<?php

namespace Binocular;

interface Entity
{
    public function getId(): string;

    public function getAction(): array;

    public function getCurrentState(): array;

    public function setCurrentState(array $newState);
}