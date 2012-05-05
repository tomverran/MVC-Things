<?php
namespace Application\Model\Repository;

/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 05/05/12
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */
class EngineRepository extends Repository {

    /**
     * @param $id
     * @return \Application\Model\Entity\Engine
     */
    public function get($id)
    {
        $this->setReturnClass('Application\Model\Entity\Engine');
        return $this->objectify(self::$db->fetchRow('SELECT * from engines WHERE id='.$id));
    }

}
