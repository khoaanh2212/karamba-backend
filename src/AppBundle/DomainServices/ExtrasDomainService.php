<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 5/09/16
 * Time: 18:17
 */

namespace AppBundle\DomainServices;


use AppBundle\DTO\ExtraResponseDTO;
use AppBundle\DTO\ExtrasDTO;
use AppBundle\DTO\PackDTO;
use AppBundle\Utils\JatoAccessor;

class ExtrasDomainService
{
    /**
     * @var JatoAccessor
     */
    private $loggedClient;

    public function __construct(JatoAccessor $jatoAccessor)
    {
        $this->loggedClient = $jatoAccessor->login();
    }

    /**
     * @param string $vehicleId
     * @param PackDTO[] $packsDTOCollection
     * @return ExtraResponseDTO
     */
    public function getExtrasForVehicleId(string $vehicleId, array $packsDTOCollection)
    {
        $return = new ExtraResponseDTO();
        $extras = $this->loggedClient->getVehiclePacksAndExtras($vehicleId)["extras"];
        for($i=0; $i < count($extras); $i++) {
            $extra = $extras[$i];
            $optionId = $extra->optionId;
            $found = false;
            foreach($packsDTOCollection as $packDTO)
            {
                if(in_array($optionId, $packDTO->excluded)) {
                    $found = true;
                    break;
                }
                if(in_array($optionId, $packDTO->required)) {
                    $found = true;
                    $return->addExtraRequiredByPack($this->constructExtraDTOFromJSON($extra), $packDTO->id);
                    break;
                }
                if(in_array($optionId, $packDTO->included)) {
                    $found = true;
                    $return->addExtraIncludedInPack($this->constructExtraDTOFromJSON($extra), $packDTO->id);
                    break;
                }
            }
            if(!$found) {
                $return->addExtra($this->constructExtraDTOFromJSON($extra));
            }
        }
        return $return;
    }

    private function constructExtraDTOFromJSON($extra, $packId = null): ExtrasDTO
    {
        return new ExtrasDTO($extra->optionId, $extra->optionName, $extra->price, $packId);
    }
}