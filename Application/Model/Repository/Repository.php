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
     * @param $result
     * @param null $class
     * @return mixed
     */
    public function objectify($result, $class=null)
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
            foreach ($result as $rows) {
                $final[] = $this->objectify($rows,$class);
            }
            return $final;
        }

        //handle objectifying the array
        $object = $class->newInstance();
        foreach ($result as $key=>$value) {
            $key = self::dbFieldToProperty($key);
            $setterMethod = self::propertyToMethod($key);
            if ($this->class->hasMethod($setterMethod)) {
                $this->class->getMethod($setterMethod)->invoke($object,$value);
            } else if ($this->class->hasProperty($key) && $this->class->getProperty($key)->isPublic()) {
                $this->class->getProperty($key)->setValue($object,$value);
            }
        }
        return $object;
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

    /**
     * Convert many things to an object
     * @param $result
     * @return array
     */
    public function objectifyMany($result) {
        $ret = array();
        foreach ($result as $key=>$value) {
            $ret[$key] = $this->objectify($value);
        }
        return $ret;
    }
}
