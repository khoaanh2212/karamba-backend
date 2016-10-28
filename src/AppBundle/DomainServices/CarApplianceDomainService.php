<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 26/08/16
 * Time: 17:27
 */

namespace AppBundle\DomainServices;


use AppBundle\DTO\ExtrasDTO;
use AppBundle\Factory\CarApplianceFactory;
use AppBundle\Registry\CarApplianceRegistry;
use AppBundle\Utils\JatoAccessor;
use AppBundle\Utils\LoggedJatoClient;
use Doctrine\ORM\EntityNotFoundException;

class CarApplianceDomainService
{
    /**
     * @var CarApplianceRegistry
     */
    private $registry;

    /**
     * @var LoggedJatoClient
     */
    private $jatoClient;

    /**
     * @var CarApplianceFactory
     */
    private $carApplianceFactory;

    public function __construct(CarApplianceRegistry $registry, JatoAccessor $jatoAccessor, CarApplianceFactory $carApplianceFactory)
    {
        $this->registry = $registry;
        $this->jatoClient = $jatoAccessor->login();
        $this->carApplianceFactory = $carApplianceFactory;
    }

    public function createAppliance(string $clientId, int $vehicleId, string $brand, string $model, array $extras, int $packageId = null, int $color = null)
    {
        $data = $this->jatoClient->getVehiclePacksAndExtras($vehicleId);
        $extraDatas = $this->jatoClient->getVehicle($brand, $model);
        $extraData = null;
        foreach($extraDatas->vehicles as $extraCarData) {
            if($extraCarData->vehicleId == $vehicleId) {
                $extraData = $extraCarData;
            }
        }
        $numberOfDoors = $extraData->numberOfDoors;
        $derivative = $extraData->derivativeToDisplay;
        $transmission = $extraData->transmission;
        $fuelType = $extraData->fuelTypeToDisplay;
        $price = $data["price"];
        $image = $data["photo"];
        $packs = $data["options"]["packs"];
        $extraData = $data["options"]["extras"];
        $packDTO = null;
        if($packageId) {
            foreach($packs as $pack) {
                if($pack->optionId == $packageId) {
                    $packData = $pack;
                    $packageName = ltrim($packData->optionName);
                    $packagePrice = $packData->price;
                    $packDTO = new ExtrasDTO($packageId, $packageName, $packagePrice);
                    $extras = array_diff($extras, $packData->includes);
                }
            }

        }
        $extraItems = array();
        $colorDTO = null;
        foreach($extraData as $extraItem) {
            $optionId = $extraItem->optionId;
            if(in_array($optionId, $extras)) {
                array_push($extraItems, new ExtrasDTO($extraItem->optionId, ltrim($extraItem->optionName), $extraItem->price));
            }
            if($color && $color == $optionId) {
                $colorDTO = new ExtrasDTO($extraItem->optionId, ltrim($extraItem->optionName), $extraItem->price);
            }
        }
        $appliance = $this->carApplianceFactory->constructCarAppliance($clientId, $vehicleId, $brand, $model, $derivative, $numberOfDoors, $transmission, $fuelType, $price, $image, $extraItems, $packDTO, $colorDTO);
        return $this->registry->saveOrUpdate($appliance);
    }

    public function getAppliancesForClient(string $clientId)
    {
        return $this->registry->findByClientId($clientId);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function delete(string $applianceId){
        $entity = $this->registry->findOneById($applianceId);
        if(!$entity) {
            throw new EntityNotFoundException("carAppliance with id [".$applianceId."] not found");
        }
        $this->registry->delete($entity);
    }

    public function getApplianceById(string $id)
    {
        return $this->registry->findOneById($id);
    }
}