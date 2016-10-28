<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 11/08/16
 * Time: 16:06
*/


use AppBundle\DomainServices\StockCarsDomainService;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\Entity\Price;
use AppBundle\Entity\StockCar;
use AppBundle\Registry\StockCarsRegistry;
use Doctrine\ORM\EntityNotFoundException;


class StockCarsDomainServiceTest extends PHPUnit_Framework_TestCase
{

    const TEST_ID = "testId";
    /**
     * @var StockCarsDomainService
     */
    private $sut;

    /**
     * @var StockCarsRegistry
     */
    private $registry;


    protected function setUp()
    {
        $this->registry = $this->getMockBuilder("AppBundle\\Registry\\StockCarsRegistry")->disableOriginalConstructor()->setMethods(
            array("delete", "findOneById")
        )->getMock();
        $this->sut = new StockCarsDomainService($this->registry);
    }

    public function test_delete_callsRegistryDeleteMethod()
    {
        $stockCar = $this->constructStockCar();
        $this->registry->expects($this->once())
            ->method("findOneById")->will($this->returnValue($stockCar));
        $this->registry->expects($this->exactly(1))->method("delete");
        $this->sut->delete(self::TEST_ID);
    }

    public function test_delete_throwsException_whenEntityNotFound()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->registry->expects($this->once())
            ->method("findOneById")->will($this->returnValue(null));
        $this->sut->delete(self::TEST_ID);
    }
    private function constructStockCar(){
        return new StockCar(
            "dealerId",
            new VehicleDTO(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
            new VehicleOptionDTO(1, 1, 1, 1, 1),
            array(),
            new Price(1, 1, 1),
            "url");
    }
}