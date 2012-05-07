<?php
namespace Application\Model\Repository;
use Application\Model\Entity\Engine;

/**
 * An Engine Repository.
 */
class EngineRepository extends Repository
{
    /**
     * Construct a basic engine repo.
     * Set our return class to Engine.
     */
    public function __construct()
    {
        $this->setTable('engines');
    }

    /**
     * Map a Car object to a row for saving.
     * @param \Application\Model\Entity\Engine $engine
     * @return array
     */
    protected function objectToRow($engine)
    {
        return array(
            'id'=>$engine->getId(),
            'suffix'=>$engine->getSuffix(),
        );
    }

    /**
     * Parse
     * @param $row
     * @return \Application\Model\Entity\Car
     */
    protected function rowToObject(array $row)
    {
        return new Engine($row['id'], $row['suffix']);
    }
}
