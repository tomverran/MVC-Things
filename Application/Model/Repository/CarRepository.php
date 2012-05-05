<?php
namespace Application\Model\Repository;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 05/05/12
 * Time: 15:59
 * To change this template use File | Settings | File Templates.
 */
class CarRepository extends \Application\Model\Repository\Repository {


    /**
     * Construct this car repo
     */
    public function __construct()
    {
        parent::__construct();
        $this->setReturnClass('\Application\Model\Entity\Car');
    }

    /**
     * Get many cars.
     * @return array
     */
    public function getCars()
    {
        return $this->objectifyMany($this->select()->query()->fetchAll());
    }

    /**
     * @param $id
     * @return \Application\Model\Entity\Car
     */
    public function get($id)
    {
        return $this->objectify($this->select()->where('id=?',$id)->query()->fetch());
    }
}
