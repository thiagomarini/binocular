<?php

namespace Binocular\Events;

use DateTime;
use PDO;

/**
 * PDO implementation of EventRepository
 * Check the expected database table structure on PdoEventRepositoryTest class
 */
class PdoEventRepository implements EventRepository
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $tableName;

    public function __construct(PDO $pdo, string $tableName)
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
    }

    public function store(Event $event)
    {
        $insert = "INSERT INTO {$this->tableName} (root_id, serialized, projection_snapshot, created_at) 
                   VALUES (:root_id, :serialized, :projection_snapshot, :created_at)";
        $stmt = $this->pdo->prepare($insert);

        // Bind parameters to statement variables
        $stmt->bindParam(':root_id', $event->getRootId());
        $stmt->bindParam(':serialized', serialize($event));
        $stmt->bindParam(':projection_snapshot', $event->getSnapshotProjectionName());
        $stmt->bindParam(':created_at', $event->getCreatedAt()->format(DATE_RFC3339_EXTENDED));

        $stmt->execute();
    }

    /**
     * @return Event[]
     */
    public function all($rootId, DateTime $from = null): array
    {
        if ($from) {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} 
                                                   WHERE root_id=:root_id 
                                                   AND created_at >= :created_at 
                                                   ORDER BY created_at ASC");

            $stmt->execute([
                'root_id' => $rootId,
                'created_at' => $from->format(DATE_RFC3339_EXTENDED)
            ]);
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE root_id=:root_id ORDER BY created_at ASC");

            $stmt->execute(['root_id' => $rootId]);
        }

        $events = [];

        foreach ($stmt->fetchAll() as $row) {
            $events[] = $this->rowToEvent($row);
        }

        return $events;
    }

    public function getFirstSnapshotAfter($rootId, string $snapshotProjectionName, DateTime $from): ?Event
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} 
                                               WHERE root_id=:root_id 
                                               AND projection_snapshot=:projection_snapshot 
                                               AND created_at >= :created_at
                                               ORDER BY created_at ASC
                                               LIMIT 1");

        $stmt->execute(['root_id' => $rootId]);
        $stmt->execute(['projection_snapshot' => $snapshotProjectionName]);
        $stmt->execute(['created_at' => $from->format(DATE_RFC3339_EXTENDED)]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->rowToEvent($row);
    }

    protected function rowToEvent(array $row): Event
    {
        return unserialize($row['serialized']);
    }
}