<?php
namespace Application\Model\Repository;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 25/03/12
 * Time: 17:56
 * To change this template use File | Settings | File Templates.
 */
abstract class Repository
{

    /**
     * @var Zend_Db
     */
    protected static $db;

    /**
     * @var \ReflectionClass
     */
    private $class;

    /**
     * @var string A table name for use with select()
     */
    private $table;

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
     * Save a domain object
     * @param $object
     */
    public function save($object)
    {
        if (!$object instanceof $this->class) {
            throw new \LogicException('Bad Object Type');
        }

        $id = null;
        $toSave = array();
        $ro = new \ReflectionObject($object);
        foreach ($ro->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->getName() != 'id') {
                $toSave[$property->getName()] = $property->getValue();
            } else {
                $id = $property->getValue();
            }
        }

        if (isset($id)) {
            self::$db->update($this->table,$toSave,'id='.self::$db->quote($id));
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
        return $this->objectify($this->select()->where('id=?',$id)->query()->fetch());
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

        return $this->objectify($this->select()
                    ->where('id IN ('.implode(',',$ids).')')
                    ->query()->fetchAll());
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
        return $this->objectify($this->select()->query()->fetchAll());
    }

    /**
     * Callback fired after objectificaton of objects
     * Allowing repositories to modify Domain Objects to fulfill relationships
     * without having to override get, getBy, getByIDs etc.
     * @param $object
     */
    protected function parseObject($object)
    {
        return $object;
    }

    /**
     * A similar callback function to the above
     * but called when multiple objects are pulled back
     * to allow for more efficient joins etc.
     * @param array $objects
     * @return array
     */
    protected function parseMany(array $objects)
    {
        return $objects;
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
     * Set the class to use when objectifying
     * @param string $class The full class name
     * @return Repository
     */
    public function setReturnClass($class) {
        $this->class = new \ReflectionClass($class);
        return $this;
    }

    /**
     * Objectify
     * @param $result
     * @param null $class
     * @return array
     */
    protected function objectify($result, $class=null)
    {
        //get a ReflectionClass, somehow
        if ($class && is_string($class)) {
            $class = new \ReflectionClass($class);
        } else if (!$class || !$class instanceof \ReflectionClass) {
            $class = $this->class;
        }

        //is this multiple objects?
        if (is_array(reset($result))) {
            $final = array();
            foreach ($result as $row) {
                $final[$row['id']] = $class->newInstance($row);
            }
            return $this->parseMany($final);
        }

        //handle objectifying a single object if not
        $object = $class->newInstance($result);
        return $this->parseObject($object);
    }

    /**
     * Convert a DB field to a property name.
     * @static
     * @param $key
     * @return mixed
     */
    protected static function dbFieldToProperty($key)
    {
        return preg_replace_callback('/_([a-z])/',function($matches) {
            return strtoupper($matches[1]);
        }, $key);
    }

    /**
     * Convert a property name to a DB field.
     * @static
     * @param $key
     * @return mixed
     */
    protected static function propertyToDbField($key)
    {
        return preg_replace_callback('/[A-Z]/',function($matches) {
            return '_'.strtolower($matches[0]);
        }, $key);
    }

    /**
     * @static
     * @param $key
     * @return string
     */
    protected static function propertyToMethod($key)
    {
        return 'set'.ucfirst($key);
    }
}
