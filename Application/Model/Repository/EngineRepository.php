<?php
namespace Application\Model\Repository;

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
        $this->setReturnClass('Application\Model\Entity\Engine');
        $this->setTable('engines');
    }
}
