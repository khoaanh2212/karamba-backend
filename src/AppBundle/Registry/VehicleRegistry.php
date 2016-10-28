<?php
/**
 * Created by IntelliJ IDEA.
 * User: roman
 * Date: 05/08/16
 * Time: 10:50
 */

namespace AppBundle\Registry;


use AppBundle\Factory\VehicleFactory;
use AppBundle\Utils\JatoAccessor;


class VehicleRegistry
{
    /**
     * @var JatoAccessor
     */
    private $jatoAccessor;

    public function __construct(JatoAccessor $jatoAccessor)
    {
        $this->jatoAccessor = $jatoAccessor;
    }

    public function getVehicle(String $brand, String $model)
    {
        $loggedAccessor = $this->jatoAccessor->login();
        $response       = $loggedAccessor->getVehicle($brand, $model);
        return VehicleFactory::summaryDTOfromJSON($response->vehicles);
    }

    public function getVehiclePerformance(string $id){
        $loggedAccessor = $this->jatoAccessor->login();
        $vehicleDetail = $loggedAccessor->getVehicleDetail($id);
        return VehicleFactory::performanceDTOfromJSON($id, $vehicleDetail->vehiclePerformance);
    }

    public function getVehicleExtrasAndPicture(string $id, string $packageId = null){
        $loggedAccessor = $this->jatoAccessor->login();
        $response = $loggedAccessor->getVehicleDetail($id);
        $photoUrl = $response->vehicleHeaderInfo->vehiclePhotoPath->photoPath;
        $extrasAndColors = VehicleFactory::categoriesDTOfromJSON($response->vehicleOptionPage->optionInfos, $packageId);
        return [
            "photoUrl"           => $photoUrl,
            "extras"             => $extrasAndColors->extras,
            "colors" => $extrasAndColors->colors
        ];
    }
}
