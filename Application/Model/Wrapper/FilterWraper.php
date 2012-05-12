<?php
namespace Application\Model\Wrapper;
use Application\Model\Repository\Repository;
/**
 * FilterWraper.php. Allows proper lazy loading
 * of domain objects fetched by filters, which is usually the case
 * when fulfilling one-to-many relationships.
 * @author Tom
 * @since 12/05/12
 */
class FilterWraper implements \ArrayAccess, \IteratorAggregate {

    /**
     * @var \Application\Model\Repository\Repository
     */
    private $repository;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $results;

    /**
     * Construct our Filter Wrapper.
     * @param \Application\Model\Repository\Repository $repository
     * @param array $filters
     */
    public function __construct(Repository $repository, array $filters)
    {
        $this->repository = $repository;
        $this->filters = $filters;
    }

    /**
     * Fetch our results if we really have to
     */
    private function initFetch()
    {
        if (!isset($this->results)) {
            $this->results = $this->repository->getBy($this->filters, Repository::EAGER_LAZY);
        }
    }

    /**
     * Unset an offset
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        $this->initFetch();
        unset($this->results[$offset]);
    }

    /**
     * Does the given offset exist?
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $this->initFetch();
        return isset($this->results[$offset]);
    }

    /**
     * Get the given offset
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->initFetch();
        return $this->results[$offset];
    }

    /**
     * Set the value at the given offset
     * @param mixed $offset
     * @param mixed $val
     */
    public function offsetSet($offset, $val)
    {
        $this->initFetch();
        $this->results[$offset] = $val;
    }

    /**
     * Get an iterator.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $this->initFetch();
        return new \ArrayIterator($this->results);
    }
}
