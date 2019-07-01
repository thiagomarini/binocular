<?php

namespace Binocular\ReadModels;

use DateTime;
use PDO;

/**
 * PDO implementation of ReadModelRepository
 * Check the expected database table structure on PdoReadModelRepositoryTest class
 */
class PdoReadModelRepository implements ReadModelRepository
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

    public function get($rootId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE root_id=:root_id");
        $stmt->execute(['root_id' => $rootId]);

        $readModel = $stmt->fetch();

        if (!$readModel) {
            return null;
        }

        return json_decode($readModel['payload'], true);
    }

    public function store($rootId, array $newState)
    {
        $existing = $this->get($rootId);

        if ($existing) {
            $sql = "UPDATE {$this->tableName} SET root_id=:root_id, payload=:payload, updated_at=:updated_at";
        } else {
            $sql = "INSERT INTO {$this->tableName} (root_id, payload, updated_at) 
                   VALUES (:root_id, :payload, :updated_at)";
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':root_id', $rootId);
        $stmt->bindParam(':payload', json_encode($newState));
        $stmt->bindParam(':updated_at', (new DateTime)->format(DATE_RFC3339_EXTENDED));

        $stmt->execute();
    }
}