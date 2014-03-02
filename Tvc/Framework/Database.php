<?php
/**
 * Database.php
 * @author Tom
 * @since 27/08/13
 */
namespace Framework;
use Framework\Configuration\Configuration;
use Peyote\Facade;
use Peyote\Select;
use Peyote\Update;
use Peyote\Delete;
use Peyote\Insert;


/**
 * Class Database
 * @package Framework
 */
class Database extends Facade {

    /**
     * @var \PDO
     */
    private static $defaultDb;

    /**
     * @var \PDO
     */
    private $db;

    /**
     * Construct our Database
     * @param \Framework\Configuration\Configuration $config config to use
     */
    public function __construct(Configuration $config)
    {
        $db = &$this->db;
        if (!$config && !self::$defaultDb) {
            $db = &self::$defaultDb;
        }

        if ($config) {
            $dbName = $config->get('dbname');
            $dbDsn = $dbName ? ';dbname='.$dbName : ';';

            $dsn = $config->get('driver') . ':host=' . $config->get('host') . $dbDsn;
            $db = new \PDO($dsn, $config->get('user'), $config->get('password'));
        }
    }

    /**
     * Query a raw sql string
     * @param $query
     */
    public function queryString($query)
    {
        $this->getAdapter()->query($query);
    }

    /**
     * Execute a Peyote query
     * @param Select|Update|Insert|Delete $query
     * @param int $fetchMode One of the PDO::FETCH_* consts.
     * @return \PDOStatement
     */
    public function query($query, $fetchMode = \PDO::FETCH_ASSOC)
    {
        $statement = $this->getAdapter()->prepare($query->compile());
        $statement->setFetchMode($fetchMode);
        $statement->execute($query->getParams());
        return $statement;
    }

    /**
     * Get the underlying PDO adapter
     * @return \PDO
     */
    public function getAdapter()
    {
        return $this->db ?: self::$defaultDb;
    }
}