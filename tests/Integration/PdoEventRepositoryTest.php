<?php

namespace Tests\Integrations;

use Binocular\Events\EventRepository;
use Binocular\Events\PdoEventRepository;
use PDO;
use Tests\BaseEventRepositoryTest;

class PdoEventRepositoryTest extends BaseEventRepositoryTest
{
    const ROOT_ID = 'foo';

    const DB_FILE = 'tests/Integration/test_db.sqlite';

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * On real implementation replace "root" by the name of the aggregate root, i.e: user_events
     * @var string
     */
    protected $table = 'root_events';

    public function setUp()
    {
        parent::setUp();

        // create DB
        $this->pdo = new PDO('sqlite:' . self::DB_FILE);

        // Set errormode to exceptions
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /**
         * Create event table
         */
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS {$this->table} (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                    root_id VARCHAR(255), 
                    serialized TEXT,
                    projection_snapshot VARCHAR(255) DEFAULT NULL, 
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL)");
    }

    public function tearDown()
    {
        parent::tearDown();

        unlink(self::DB_FILE);
    }

    protected function getRepository(): EventRepository
    {
        return new PdoEventRepository($this->pdo, $this->table);
    }
}