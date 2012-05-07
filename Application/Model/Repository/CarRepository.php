<?php
namespace Application\Model\Repository;
/**
 * Created by JetBrains PhpStorm.
 * User: Tom
 * Date: 05/05/12
 * Time: 15:59
 * To change this template use File | Settings | File Templates.
 */
class CarRepository extends \Application\Model\Repository\Repository
{

    /**
     * Engine Repository to fulfill relationships
     * @var \Application\Model\Repository\Repository
     */
    private $engineRepository;

    /**
     * Construct this CarRepository
     * @param Repository $engineRepository DI for engines.
     */
    public function __construct(Repository $engineRepository)
    {
        parent::__construct();
        $this->engineRepository = $engineRepository;
        $this->setReturnClass('\Application\Model\Entity\Car');
        $this->setTable('cars');
    }

    /**
     * Parse a Car.
     * @param Application\Model\Entity\Car $object
     */
    public function parseObject($object)
    {
        $object->setEngine($this->engineRepository->get($object->getEngineId()));
        return $object;
    }

    /**
     * parse many cars
     * @param array $objects
     */
    public function parseMany(array $objects)
    {
        $ids = array_map(function($object) {
            return $object->getId();
        },$objects);

        $engines = $this->engineRepository->getByIds($ids);
        foreach ($objects as $car) {
            $car->setEngine($engines[$car->getEngineId()]);
        }
        return $objects;
    }

}
