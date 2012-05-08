<?php
namespace Application\Model\Entity;
use Application\Model\Repository\Repository;
/**
 * "Smoke and mirrors, Roy" ~ Maurice Moss
 * Wrapper.php - Allow domain objects
 * to be transparently lazy loaded by
 * pretending to be one.
 * @author Tom
 * @since 08/05/12
 */
class Wrapper
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
     * Pass through method calls to our object
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!isset($this->object)) {
            $mode = $this->repository->getMode();
            $this->repository->setMode(Repository::EAGER);
            $this->object = $this->repository->get($this->id);
            $this->repository->setMode($mode);
        }
        return call_user_func_array(array($this->object,$method),$args);
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
