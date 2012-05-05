<?php
namespace Application\Model\Repository;
use \Application\Library\Database;
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
        return $this->objectifyMany(self::$db->fetchAll('SELECT * FROM cars'));
    }

    /**
     * @param $id
     * @return \Application\Model\Entity\Car
     */
    public function get($id)
    {
        return $this->objectify(self::$db->fetchRow('SELECT * FROM cars WHERE id='.$id));
    }
}
