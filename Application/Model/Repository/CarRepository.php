<?php
namespace Application\Model\Repository;
use Application\Model\Entity\Car;
/**
 * A Car Repository
 * @author Tom
 * @since 05/05/12
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
        $this->setTable('cars');
    }

    /**
     * Map a Car object to a row for saving.
     * @param \Application\Model\Entity\Car $car
     * @return array
     */
    protected function objectToRow($car)
    {
        return array(
            'id'=>$car->getId(),
            'name'=>$car->getName(false),
            'chassis_id'=>$car->getChassisId(),
            'engine_id'=>$car->getEngineId()
        );
    }

    /**
     * Parse
     * @param $row
     * @return \Application\Model\Entity\Car
     */
    protected function rowToObject(array $row)
    {
        $h = $this->getPerformanceHint() == Repository::EAGERLAZY ? Repository::LAZY : $this->getPerformanceHint();
        $old = $this->engineRepository->getPerformanceHint();
        $this->engineRepository->setPerformanceHint($h);

        $car = new Car($row['id'], $row['engine_id'], $row['chassis_id'], $row['name']);
        $car->setEngine($this->engineRepository->get($car->getEngineId()));
        $this->engineRepository->setPerformanceHint($old);
        return $car;
    }

    /**
     * parse many cars
     * @param array $rows
     * @return array
     */
    protected function rowsToObjects(array $rows)
    {
        $ids = array_map(function($row) {
            return $row['id'];
        },$rows);

        //fetch in engines required by our cars.
        $engines = $this->engineRepository->getByIds($ids);
        $final = array();

        //re-create our cars.
        foreach ($rows as $row) {
            $car = new Car($row['id'], $row['engine_id'], $row['chassis_id'], $row['name']);
            $car->setEngine($engines[$row['engine_id']]);
            $final[$row['id']] = $car;
        }
        return $final;
    }
}
