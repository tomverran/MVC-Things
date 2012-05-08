<?php
namespace Application\Model\Wrapper;
use Application\Model\Repository\Repository;
/**
 * "Smoke and mirrors, Roy" ~ Maurice Moss
 * EntityWrapper.php - Allow specific domain objects
 * to be transparently lazy loaded by
 * pretending to be one.
 * @author Tom
 * @since 08/05/12
 */
class EntityWrapper
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Object
     */
    private $object;

    /**
     * @param int $id
     * @param \Application\Model\Repository\Repository $repository
     */
    public function __construct($id, Repository $repository)
    {
        $this->id = $id;
        $this->repository = $repository;
    }

    /**
     * Load our object if it isn't
     * already loaded.
     */
    private function initObject()
    {
        if (!isset($this->object)) {
            $mode = $this->repository->getPerformanceHint();
            $this->repository->setPerformanceHint(Repository::EAGERLAZY);
            $this->object = $this->repository->get($this->id);
            $this->repository->setPerformanceHint($mode);
        }
    }

    /**
     * Pass through method calls to our object
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $this->initObject();
        return call_user_func_array(array($this->object,$method),$args);
    }

    /**
     * Get a public field
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $this->initObject();
        return $this->object->$name;
    }

    /**
     * Set a public field
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->initObject();
        $this->object->$name = $value;
    }

    /**
     * We know the ID of our object
     * so assuming it has a getId method we can
     * return it without loading the entire object.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
