<?php
namespace Application\Model\Entity;

/**
 * An engine entity.
 * @author Tom
 */
class Engine
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $suffix;

    /**
     * Set the ID of this Engine
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set the name of this Engine
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set the suffix of this engine
     * @param $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Get the suffix of this engine (tdi, 16v, etc)
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }
}
