<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 29/07/16
 * Time: 18:50
 */

namespace AppBundle\Registry;


use AppBundle\DTO\CarModelDTO;
use AppBundle\DTO\PackItemDTO;
use AppBundle\Utils\JatoAccessor;

class JatoRegistry
{
    /**
     * @var JatoAccessor
     */
    private $jatoAccessor;

    public function __construct(JatoAccessor $jatoAccessor)
    {
        $this->jatoAccessor = $jatoAccessor;
    }

    public function getBrands()
    {
        $result = array();
        $loggedAccessor = $this->jatoAccessor->login();
        $brands = $loggedAccessor->getBrands();
        foreach($brands->makes as $brand) {
            array_push($result, ['name' => $brand->makeNameToDisplay]);
        }
        return $result;
    }

    public function getBrandsModels(array $brands)
    {
        $result = array();
        if($brands) {
            foreach ($brands as $brand) {
                $result[$brand] = array();
            }
            $loggedAccessor = $this->jatoAccessor->login();
            $models = $loggedAccessor->getBrandsModels($brands);
            foreach ($models->models as $model) {
                $carModel = new carModelDTO($model->makeNameToDisplay, $model->modelNameToDisplay, $model->modelYearToDisplay);
                if(!is_null($result[$model->makeNameToDisplay])) array_push($result[$model->makeNameToDisplay], $carModel);
            }
        }
        return $result;
    }


    public function getPacks(string $modelId)
    {
        $return = array();
        $loggedAccessor = $this->jatoAccessor->login();
        $packsAndExtras = $loggedAccessor->getVehiclePacksAndExtras($modelId)["options"];
        $packs = $packsAndExtras["packs"];
        $extras = $packsAndExtras["extras"];
        $extrasNameMap = array();
        foreach($extras as $extra) {
            $extrasNameMap[$extra->optionId] = $extra->optionName;
        }
        foreach($packs as $pack) {
            $id = $pack->optionId;
            $name = $pack->optionName;
            $price = $pack->price;
            $includes = array();
            foreach($pack->includes as $include) {
                if(array_key_exists($include, $extrasNameMap)) {
                    array_push($includes, $extrasNameMap[$include]);
                }
            }
            array_push($return, new PackItemDTO($id, $name, $includes, $price));
        }
        return $return;
    }

}
