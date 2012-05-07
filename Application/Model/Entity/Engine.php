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

    public function __construct($row=null)
    {
        if (is_array($row)) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->suffix = $row['suffix'];
        }

    }

    /**
     * Get the ID.
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
