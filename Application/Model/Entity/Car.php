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

    public function __construct()
    {
        $this->engines = new EngineRepository();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEngineId($id)
    {
        $this->engineId = $id;
    }

    public function setChassisId($id)
    {
        $this->chassis_id = $id;
    }

    public function getName()
    {
        $engine = $this->engines->get($this->engineId);
        return $this->name.' '.$engine->getSuffix();
    }

    public function getEngine()
    {
        return $this->engines->get($this->engineId);
    }

}
