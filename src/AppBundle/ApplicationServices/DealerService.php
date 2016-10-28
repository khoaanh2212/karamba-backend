<?php

namespace AppBundle\ApplicationServices;

use AppBundle\DomainServices\AvailableCarsDomainService;
use AppBundle\DomainServices\AvatarDomainService;
use AppBundle\DomainServices\CarDomainService;
use AppBundle\DomainServices\DealerApplicationDomainService;
use AppBundle\DomainServices\DealerBackgroundImageDomainService;
use AppBundle\DomainServices\DealerConditionDomainService;
use AppBundle\DomainServices\DealerDomainService;
use AppBundle\DomainServices\StockCarsDomainService;
use AppBundle\DTO\AvailableCarModelDTO;
use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\DealerDTO;
use AppBundle\DTO\DealerWithConditionsDTO;
use AppBundle\DTO\VehicleDTO;
use AppBundle\DTO\VehicleOptionDTO;
use AppBundle\Entity\AvailableCars;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\Price;
use AppBundle\Entity\StockCar;
use AppBundle\Factory\VehicleFactory;
use AppBundle\Utils\GoogleMapsAccessor;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DealerService
{
    /**
     * @var DealerDomainService
     */
    private $dealerDomainService;

    /**
     * @var DealerApplicationDomainService
     */
    private $dealerApplicationDomainService;

    /**
     * @var DealerConditionDomainService
     */
    private $dealerConditionDomainService;

    /**
     * @var AvatarDomainService
     */
    private $avatarDomainService;

    /**
     * @var DealerBackgroundImageDomainService
     */
    private $dealerBackgroundDomainService;

    /**
     * @var AvailableCarsDomainService
     */
    private $availableCarsDomainService;

    /**
     * @var CarDomainService
     */
    private $carDomainService;

    /**
     * @var StockCarsDomainService
     */
    private $stockCarsDomainService;

    /**
     * @var GoogleMapsAccessor
     */
    private $googleMapsAccessor;

    public function __construct(DealerDomainService $dealerDomainService, DealerApplicationDomainService $dealerApplicationDomainService, DealerConditionDomainService $dealerConditionDomainService, AvatarDomainService $avatarDomainService, DealerBackgroundImageDomainService $dealerBackgroundDomainService, AvailableCarsDomainService $availableCarsDomainService, CarDomainService $carDomainService, StockCarsDomainService $stockCarsDomainService, GoogleMapsAccessor $googleMapsAccessor)
    {
        $this->dealerDomainService = $dealerDomainService;
        $this->dealerApplicationDomainService = $dealerApplicationDomainService;
        $this->dealerConditionDomainService = $dealerConditionDomainService;
        $this->avatarDomainService = $avatarDomainService;
        $this->dealerBackgroundDomainService = $dealerBackgroundDomainService;
        $this->availableCarsDomainService = $availableCarsDomainService;
        $this->carDomainService = $carDomainService;
        $this->stockCarsDomainService = $stockCarsDomainService;
        $this->googleMapsAccessor = $googleMapsAccessor;
    }

    public function createDealer(string $token, string $password)
    {
        $accepted = $this->dealerApplicationDomainService->retrieveApplicationAndValidate($token);
        $this->dealerApplicationDomainService->processApplication($accepted);
        $this->dealerDomainService->createDealer($accepted->getName(), $accepted->getPhoneNumber(), $accepted->getVendorName(), $accepted->getVendorRole(), $accepted->getMail(), $password);
    }

    public function getDealerById(string $id): DealerWithConditionsDTO
    {
        $dealer = $this->dealerDomainService->getDealerById($id);
        $dealerDTO = $dealer->toDTO();
        $position = $dealer->getPosition();
        if ($position) {
            $dealerDTO->setPosition($position);
        }
        $conditions = $this->dealerConditionDomainService->getAllConditions();
        $conditionsDTO = array();
        foreach ($conditions as $condition) {
            array_push($conditionsDTO, $condition->toDTO());
        }
        $dealerWithConditionsDTO = new DealerWithConditionsDTO($dealerDTO, $conditionsDTO);
        $avatar = $this->avatarDomainService->getAvatarByDealerId($id);
        $backgroundImage = $this->dealerBackgroundDomainService->getBackgroundImageByDealerId($id);
        if ($avatar) {
            $dealerWithConditionsDTO->addAvatar($avatar->toDTO());
        }
        if ($backgroundImage) {
            $dealerWithConditionsDTO->addBackgroundImage($backgroundImage->toDTO());
        }
        return $dealerWithConditionsDTO;
    }

    public function updateDealer(string $id, string $dealerName = null, string $description = null, string $phoneNumber = null, string $vendorName = null, string $vendorRole = null, string $password = null, string $address = null, string $scheduling = null, string $deliveryConditions = null, string $specialConditions = null, array $generalConditons = null, UploadedFile $avatarFile = null, UploadedFile $backgroundFile = null, string $zipCode = null)
    {
        $position = null;
        if ($zipCode) {
            $position = $this->googleMapsAccessor->getPositionFromZipCode($zipCode);
        }
        $this->dealerDomainService->updateDealer($id, $dealerName, $description, $phoneNumber, $vendorName, $vendorRole, $password, $address, $scheduling, $deliveryConditions, $specialConditions, $generalConditons, $zipCode, $position["position"]);
        if ($avatarFile) {
            $this->avatarDomainService->createAvatarFromUploadFile($avatarFile, $id);
        }
        if ($backgroundFile) {
            $this->dealerBackgroundDomainService->createBackgroundImageFromUploadFile($backgroundFile, $id);
        }
    }

    public function updateAvailableCars(string $id, $carModelsByBrand)
    {
        $allBrands = array();
        foreach ($carModelsByBrand as $brand => $models) {
            $result = array();
            foreach ($models as $model) {
                if (property_exists($model, 'available') && $model->available) {
                    array_push($result, new CarModelDTO($model->brand, $model->name, $model->year));
                }
            }
            array_push($allBrands, new AvailableCars($id, $brand, $result));
        }
        $this->availableCarsDomainService->updateAvailableCarsByDealer($allBrands, $id);
    }

    public function getAvailableCars(string $id)
    {
        $models = $this->availableCarsDomainService->retrieveAvailableCarsByDealer($id);
        $modelsDTOByBrand = array();
        foreach ($models as $model) {
            $modelsDTOByBrand[$model->getBrand()] = $model->modelsToDTO();
        }
        return $modelsDTOByBrand;
    }

    public function getCarsWithAvailability(string $id)
    {
        $availableCars = $this->getAvailableCars($id);
        $availableBrands = array_keys($availableCars);
        $allCars = $this->carDomainService->getBrandsModels($availableBrands);

        $result = array();
        foreach ($allCars as $brand => $models) {
            $result[$brand] = array();
            $availableModels = $availableCars[$brand];
            foreach ($models as $model) {
                $selected = $this->_checkIfExist($model, $availableModels);
                array_push($result[$brand], new AvailableCarModelDTO($model->brand, $model->name, $model->year, $selected));
            }
        }
        return $result;
    }

    private function _checkIfExist($model, $arrayModels) : bool
    {
        foreach ($arrayModels as $m) {
            if ($m->comparison($model)) {
                return true;
            }
        }
        return false;
    }


    public function addStockCar(string $dealerId, $stockCar)
    {
        $car = $this->_buildEntityFromJSON($dealerId, $stockCar);
        $this->stockCarsDomainService->addStockCar($car);
        $storedCars = $this->getStockCars($dealerId, $car->getId());
        return $storedCars;
    }

    public function updateStockCar(string $dealerId, $stockCar)
    {
        $car = $this->_buildEntityFromJSON($dealerId, $stockCar);
        $car->setId($stockCar->id);
        $this->stockCarsDomainService->addStockCar($car);
        $storedCars = $this->getStockCars($dealerId);
        return $storedCars;
    }

    public function deleteStockCar(string $id)
    {
        $this->stockCarsDomainService->delete($id);
    }

    public function _buildEntityFromJSON(string $dealerId, $stockCar)
    {
        $vehicle = VehicleFactory::vehicleDTOfromJSON($stockCar->engine);
        $color = null;
        if ($stockCar->color != null) {
            $color = VehicleFactory::vehicleOptionDTOfromJSON($stockCar->color);
        }
        $extras = array();
        foreach ($stockCar->extras as $extra) {
            array_push($extras, VehicleFactory::vehicleOptionDTOfromJSON($extra));
        }
        $price = new Price($stockCar->pvp, $stockCar->cash, $stockCar->discount);
        $packages = property_exists($stockCar, "packages")?$stockCar->packages:null;
        return new StockCar($dealerId, $vehicle, $color, $extras, $price, $stockCar->photoUrl, $packages);
    }

    public function getStockCars(string $dealerId, string $newCar = null)
    {
        $cars = $this->stockCarsDomainService->retrieveStockCarsByDealer($dealerId);
        $result = array();
        foreach ($cars as $car) {
            array_push($result, $this->setIsNew($car->toDTO(), $newCar));
        }
        return array("stock" => $result);
    }

    public function getRating(string $dealerId)
    {
        $rating = $this->dealerDomainService->getReviewsByDealer($dealerId);
        return array('rating' => $rating);
    }

    private function setIsNew($car, $newCarId)
    {
        if ($car["id"] == $newCarId) {
            $car["isNew"] = true;
            return $car;
        }
        $car["isNew"] = false;
        return $car;
    }
}
