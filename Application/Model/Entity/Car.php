<?php
namespace Application\Model\Entity;
use Application\Model\Repository\EngineRepository;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 05/05/12
 * Time: 15:54
 * To change this template use File | Settings | File Templates.
 */
class Car {

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $chassis_id;

    /**
     * @var int
     */
    private $engineId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var EngineRepository
     */
    private $engines;

    /**
     * Construct our Car.
     */
    public function __construct()
    {
        $this->engines = new EngineRepository();
    }

    /**
     * Set the ID field
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set the car's name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set the engine ID
     * @param int $id
     */
    public function setEngineId($id)
    {
        $this->engineId = $id;
    }

    /**
     * Set the chassis ID
     * @param int $id
     */
    public function setChassisId($id)
    {
        $this->chassis_id = $id;
    }

    /**
     * Get the car's name, optionally with engine suffix.
     * @param bool $suffix Whether to include the engine suffix
     * @return string The car name
     */
    public function getName($suffix=true)
    {
        $post = $suffix ? $engine = $this->engines->get($this->engineId)->getSuffix() : '';
        return $this->name.' '.$post;
    }

    /**
     * Get this car's engine.
     * @return Engine
     */
    public function getEngine()
    {
        return $this->engines->get($this->engineId);
    }
}
