<?php
namespace Application\Model\Repository;
use Application\Model\Wrapper\EntityWrapper;
/**
 * A repository class that handles accessing and storing objects,
 * using an interface similar to that of an in memory data structure.
 * Internally the repository is responsible for mapping between DB rows
 * and domain objects. The behaviour to do this is implemented by subclasses.
 * @since 25/03/12
 * @author Tom
 */
abstract class Repository
{
    /**
     * @var Zend_Db
     */
    protected static $db;

    /**
     * @var string A table name for use with select()
     */
    private $table;

    /**
     * @var int
     */
    const EAGER_LAZY = 0;
    const EAGER = 1;
    const LAZY = 2;

    /**
     * Construct a new repository.
     * This class is abstract but subclasses should call this
     * since it checks to see if the Db is initialised.
     */
    public function __construct()
    {
        if (!isset(self::$db)) {
            self::$db = \Zend_Db::factory('mysqli',array('host'=>'localhost',
                                                         'dbname'=>'car',
                                                         'password'=>'',
                                                         'username'=>'root'));
        }
    }

    /**
     * Parse a performance hint
     * @param int $hint
     * @return int
     */
    private function parseHint($hint)
    {
        return $hint == Repository::EAGER_LAZY ? Repository::LAZY : $hint;
    }

    /**
     * Set the table to use for selects.
     * @param string $table The table name
     * @return Repository
     */
    protected function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Get a select object
     * @param array $cols
     * @return \Zend_Db_Select
     */
    protected function select(array $cols = array('*'))
    {
        return self::$db->select()->from($this->table,$cols);
    }

    /**
     * Save a domain object
     * @param $object
     */
    public function save($object)
    {
        $toSave = $this->objectToRow($object);
        if (isset($toSave['id'])) {
            self::$db->update($this->table,$toSave,'id='.self::$db->quote($toSave['id']));
        } else {
            self::$db->insert($this->table,$toSave);
        }
    }

    /**
     * Delete a domain object
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        return self::$db->delete($this->table,'id='.self::$db->quote($id));
    }

    /**
     * Get a single domain object
     * @param int $id The object ID
     * @param int $hint The performance hint to apply
     * @return \Application\Model\Wrapper\EntityWrapper|void
     */
    public function get($id, $hint=Repository::EAGER)
    {
        if ($hint != Repository::LAZY) {
            return $this->rowToObject($this->select()->where('id=?',$id)->query()->fetch(), $this->parseHint($hint));
        } else {
            return new EntityWrapper($id, $this);
        }
    }

    /**
     * Get multiple domain objects
     * @param array $ids The IDs of objects to fetch
     * @param int $hint Performance hint to apply to each
     * @return array of domain objects.
     */
    public function getByIds(array $ids, $hint=Repository::EAGER)
    {
        if (!count($ids)) {
            return array();
        } else {
            foreach ($ids as &$id) {
                $id = self::$db->quote($id);
            }
        }
        if ($hint != Repository::LAZY) {
            return $this->rowsToObjects($this->select()
                        ->where('id IN ('.implode(',',$ids).')')
                        ->query()->fetchAll(), $this->parseHint($hint));
        } else {
            $final = array();
            foreach ($ids as $id) {
                $final[$id] = new EntityWrapper($id, $this);
            }
            return $final;
        }
    }

    /**
     * Get domain objects by filters
     * @param array $filters
     * @return array
     */
    public function getBy(array $filters)
    {
        $select = $this->select();
        foreach ($filters as $col=>$val) {
            $select->where(self::$db->quoteIdentifier($col).'=?',$val);
        }
        return $this->rowsToObjects($this->select()->query()->fetchAll(), Repository::EAGER);
    }

    /**
     * Convert rows to domain objects
     * @param array $row The row to convert
     * @param $hint Performance hint for associations
     * @abstract
     */
    protected abstract function rowToObject(array $row, $hint);

    /**
     * Convert many rows to domain objects
     * @param array $rows the rows to convert
     * @param $hint Performance hint to use .
     * @return array of domain objects
     */
    protected function rowsToObjects(array $rows, $hint)
    {
        $final = array();
        foreach ($rows as $row) {
            $final[$row['id']] = $this->rowToObject($row, $hint);
        }
        return $final;
    }

    /**
     * Map a domain object to a row
     * @param \stdClass $object
     * @return array
     * @abstract
     */
    protected abstract function objectToRow($object);
}
