<?php
/**
 * Database.php
 * @author Tom
 * @since 27/08/13
 */
namespace Framework;
use Peyote\Facade;
use Peyote\Select;
use Peyote\Update;
use Peyote\Delete;


/**
 * Class Database
 * @package Framework
 */
class Database extends Facade {

    use Configurable;

    /**
     * @var \PDO
     */
    private $db;

    /**
     * Construct our DBAL
     */
    public function __construct()
    {
        $config = self::getConfig();
        $dsn = $config->get('driver') . ':host=' . $config->get('host') . ';dbname=' . $config->get('dbname');
        $this->db = new \PDO($dsn, $config->get('user'), $config->get('password'));
    }

    /**
     * Execute a Peyote query
     * @param Select|Update|Delete $query
     * @param int $fetchMode One of the PDO::FETCH_* consts.
     * @return \PDOStatement
     */
    public function query($query, $fetchMode = \PDO::FETCH_ASSOC)
    {
        $statement = $this->db->prepare($query->compile());
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
        return $this->db;
    }
}