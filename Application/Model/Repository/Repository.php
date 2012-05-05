<?php
namespace Application\Model\Mapper;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 25/03/12
 * Time: 17:56
 * To change this template use File | Settings | File Templates.
 */
abstract class Repository extends \Framework\Singleton {

    /**
     * @var \ReflectionClass
     */
    private $class;

    /**
     * Set the class to return
     * @param string $class
     */
    public function setReturnClass($class) {
        $this->class = new \ReflectionClass($class);
    }

    /**
     * Convert a result array into domain objects
     * @param array $result The result array to convert
     * @param string|null $class the object type to use
     * @return mixed
     */
    public function objectify($result, $class=null) {

        //grab an instance of our class
        if (!$class) $class = $this->class;
        $object = $class->newInstance();

        foreach ($result as $key=>$value) {
            $setterMethod = 'set'.ucfirst($key);
            if ($this->class->hasMethod($setterMethod)) {

                $object->$setterMethod($value);

            } else if ($this->class->hasProperty($key)) {
                $object->$key = $value;
            }
        }
        return $object;
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
