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
    private $mode;
    const EAGERLAZY = 0;
    const EAGER = 1;
    const LAZY = 2;

    /**
     * Construct a new repository.
     * This class is abstract but subclasses should call this
     * since it checks to see if the Db is initialised.
     */
    public function __construct()
    {
        $this->mode = self::EAGER;
        if (!isset(self::$db)) {
            self::$db = \Zend_Db::factory('mysqli',array('host'=>'localhost',
                                                         'dbname'=>'car',
                                                         'password'=>'',
                                                         'username'=>'root'));
        }
    }

    /**
     * Set Performance Hint, one of Repository::EAGER, Repository::LAZY.
     * Repository::EAGER will fetch objects as normal when requested,
     * Repository::LAZY will wrap objects and only fetch them when they're used.
     * @param $mode
     * @throws \LogicException
     */
    public function setPerformanceHint($mode)
    {
        if (in_array($mode,array(Repository::EAGER,Repository::LAZY,Repository::EAGERLAZY))) {
            $this->mode = $mode;
        } else {
            throw new \LogicException('Bad Mode');
        }
    }

    /**
     * Get the performance hint, mainly used internally.
     * See above for a more detailed description.
     * @return int
     */
    public function getPerformanceHint()
    {
        return $this->mode;
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
     * Save a domain object.
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
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($this->mode != Repository::LAZY) {
            return $this->rowToObject($this->select()->where('id=?',$id)->query()->fetch());
        } else {
            return new EntityWrapper($id, $this);
        }
    }

    /**
     * Get Domain Objects by IDs.
     * @param array $ids
     * @return array|mixed
     */
    public function getByIds(array $ids)
    {
        if (!count($ids)) {
            return array();
        } else {
            foreach ($ids as &$id) {
                $id = self::$db->quote($id);
            }
        }
        if ($this->mode != Repository::LAZY) {
            return $this->rowsToObjects($this->select()
                        ->where('id IN ('.implode(',',$ids).')')
                        ->query()->fetchAll());
        } else {
            $final = array();
            foreach ($ids as $id) {
                $final[$id] = new EntityWrapper($id, $this);
            }
            return $final;
        }
    }

    /**
     * Get Domain Objects by filters
     * @param array $filters
     * @return mixed
     */
    public function getBy(array $filters)
    {
        $select = $this->select();
        foreach ($filters as $col=>$val) {
            $select->where(self::$db->quoteIdentifier($col).'=?',$val);
        }
        return $this->rowsToObjects($this->select()->query()->fetchAll());
    }

    /**
     * Map
     * @abstract
     * @param array $row
     */
    protected abstract function rowToObject(array $row);

    /**
     * Map many rows to domain objects
     * @param array $rows
     * @return array
     */
    protected function rowsToObjects(array $rows)
    {
        $final = array();
        foreach ($rows as $row) {
            $final[$row['id']] = $this->rowToObject($row);
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
