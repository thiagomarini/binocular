<?php

namespace Tests\Integrations;

use Binocular\ReadModels\PdoReadModelRepository;
use Binocular\ReadModels\ReadModelRepository;
use PDO;
use Tests\BaseReadModelRepositoryTest;

class PdoReadModelRepositoryTest extends BaseReadModelRepositoryTest
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
    protected $table = 'root_read';

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
                                    root_id VARCHAR(255) PRIMARY KEY NOT NULL, 
                                    payload TEXT,
                                    updated_at TIMESTAMP NOT NULL)");
    }

    public function tearDown()
    {
        parent::tearDown();

        unlink(self::DB_FILE);
    }

    protected function getRepository(): ReadModelRepository
    {
        return new PdoReadModelRepository($this->pdo, $this->table);
    }
}