<?php
namespace Application\Model\Entity;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 05/05/12
 * Time: 16:55
 * To change this template use File | Settings | File Templates.
 */
class Engine {

    private $id;

    private $name;

    private $suffix;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

}
