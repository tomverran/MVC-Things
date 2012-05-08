<?php
namespace Application\Model\Entity;
use Application\Model\Repository\EngineRepository;

/**
 * A Car.
 * @author Tom
 * @since dunno
 */
class Car {

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $chassisId;

    /**
     * @var int
     */
    private $engineId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Engine
     */
    private $engine;

    /**
     * Construct our car
     * @param $id
     * @param int $engine
     * @param int $chassis
     */
    public function __construct($id, $engine, $chassis, $name)
    {
        $this->id = $id;
        $this->engineId = $engine;
        $this->chassisId = $chassis;
        $this->name = $name;
    }

    /**
     * Get the UID of this car
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Engine ID
     * @return int
     */
    public function getEngineId()
    {
        return $this->engineId;
    }

    /**
     * Get Chassis ID
     * @return int
     */
    public function getChassisId()
    {
        return $this->chassisId;
    }

    /**
     * Get the car's name, optionally with engine suffix.
     * @param bool $suffix Whether to include the engine suffix
     * @return string The car name
     */
    public function getName($suffix=true)
    {
        $post = $suffix ? $this->engine->getSuffix() : '';
        return $this->name.' '.$post;
    }

    /**
     * Set our name
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Engine $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
        $this->engineId = $engine->getId();
    }

    /**
     * Get this car's engine.
     * @return Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
