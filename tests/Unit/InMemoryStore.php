<?php

namespace Tests\Unit;

use Binocular\Action;
use Binocular\Entity;
use Binocular\Store;

class InMemoryStore implements Store
{
    /**
     * @var Entity[]
     */
    private static $entities = [];

    public function save(string $id, Action $action)
    {
        $actions = $this->getActions();

        if (!isset($actions[$action->getName()][$action->getVersion()])) {
            throw new \RuntimeException(
                sprintf('Action %s version %s not found', $action->getName(), $action->getVersion())
            );
        }

        $applicableAction = $actions[$action->getName()][$action->getVersion()];

        if (!is_callable($applicableAction)) {
            throw new \RuntimeException(
                sprintf('Action %s version %s is not callable', $action->getName(), $action->getVersion())
            );
        }

        $entity = $this->get($id);

        $entity->setCurrentState($applicableAction($entity->getCurrentState()));

        $this->persist($entity, $action);
    }

    public function get(string $id): ?Entity
    {
        if (isset(self::$entities[$id])) {
            return end(self::$entities[$id]);
        }

        return null;
    }

    private function persist(Entity $entity, Action $action)
    {
        if (!isset(self::$entities[$entity->getId()])) {
            self::$entities[$entity->getId()] = [];
        }

        $versions = count(self::$entities[$entity->getId()]);

        self::$entities[$entity->getId()][$versions++] = $entity;
    }

    /**
     * All versioned actions should be kept in here
     */
    private function getActions(): array
    {
        return [
            'name' => [
                'version' => function (array $currentState): array {
                    $newState = [];

                    return $newState;
                }
            ]
        ];
    }
}