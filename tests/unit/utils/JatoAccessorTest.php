<?php


use AppBundle\Config;
use AppBundle\Utils\JatoAccessor;
use AppBundle\Utils\LoggedJatoClient;
use AppBundle\Utils\VehicleOptionsFilter;

class JatoAccessorTest extends PHPUnit_Framework_TestCase
{
    const USERNAME = "evgeny.predein@apiumtech.com";
    const PASSWORD = "gold6dust";
    const API_HOST = "https://webapi-live.jato.com/jato.carspecs.api";
    const MARKET = "SSCE_CS2002";
    const BRAND = "ALFA ROMEO";
    const VEHICLE_ID = 725686120150525;
    const VEHICLE_WITH_EXTRAS = 770434720160601;
    /**
     * @var LoggedJatoClient
     */
    private $sut;

    protected function setUp()
    {
        $jatoAccessor = new JatoAccessor(self::API_HOST, self::USERNAME, self::PASSWORD, self::MARKET, new Config(), new VehicleOptionsFilter());
        $this->sut = $jatoAccessor->login();
    }

    public function test_login_shouldReturnLoggedInstance()
    {
        $this->assertEquals(self::USERNAME, $this->sut->getUserName());
        $this->assertNotNull($this->sut->getToken());
    }

    public function test_getBrands_shouldReturnTheBrands()
    {
        $actual = $this->sut->getBrands();
        $this->assertEquals(self::MARKET, $actual->databaseName);
        $this->assertTrue(count($actual->makes) > 0);
    }

    public function test_getModels_shouldReturnTheModels()
    {
        $actual = $this->sut->getBrandsModels(array(self::BRAND));
        $this->assertNotNull($actual->models);
    }

    public function test_getVehicles_shouldReturnTheVehicles()
    {
        $actual = $this->sut->getVehicles(self::BRAND);
        $this->assertNotNull($actual->vehicles);
    }

    public function test_getVehiclePacks_shouldReturnThePacks()
    {
        $A_VEHICLE_WITH_PACKS = self::VEHICLE_WITH_EXTRAS;
        $actual = $this->sut->getVehiclePacksAndExtras($A_VEHICLE_WITH_PACKS);
        $this->assertEquals("{\"75\":{\"optionImage\":\"https:\/\/sslphotos.jato.com\/OptionIcons\/option.png\",\"vehicleId\":770434720160601,\"optionId\":1154,\"optionType\":\"O\",\"optionName\":\" [PB4] Paquete Black line\",\"currencyCode\":null,\"attributes\":[\"Includes:,Incluye:\",\"[4ZD] Paquete brillo estilo ne\",\"And|y\",\"[VW1] Cristales oscuros en par\",\"And|y\",\"[6FJ] Carcasa de los retroviso\"],\"specsDatabaseName\":\"SSCE_CS2002\",\"includes\":[1171,1068,1164],\"requires\":[],\"excludes\":[],\"ifNotbuiltRequiredInfo\":[],\"ifBuiltRequires\":[],\"priceChanges\":[],\"discountOptionInfos\":[],\"excludedByIncludingOptionInfos\":[],\"includesOptions\":null,\"requiresOptions\":null,\"excludesOptions\":null,\"ifNotBuiltOptions\":null,\"priceChangeOptions\":null,\"discountOptions\":null,\"retailPrice902\":708.46,\"basePrice903\":585.5,\"countryPrice904\":null,\"countryPrice905\":null,\"retailPriceWithDelivery906\":null,\"categoryName\":\"Others\",\"translatedCategoryName\":null,\"optionTypeName\":\"Option\",\"price\":708.46,\"priceChange\":null,\"displayPrice\":\"\u20ac708\",\"startDate\":null,\"endDate\":null,\"optionState\":0,\"unbuild\":false,\"requiredBy\":[],\"includedBy\":[],\"changedPricedOptionIds\":[],\"changingChangedOptionInfos\":[],\"removedRequiredOptions\":null,\"optionCode\":\"PB4\",\"builtByCompareEquip\":false,\"schemaId\":0},\"76\":{\"optionImage\":\"https:\/\/sslphotos.jato.com\/OptionIcons\/option.png\",\"vehicleId\":770434720160601,\"optionId\":1157,\"optionType\":\"P\",\"optionName\":\" [WAV] Paquete Visi\u00f3n\",\"currencyCode\":null,\"attributes\":[\"Includes:,Incluye:\",\"[9ZE] Audi Phone Box\",\"If:|Si:\",\"Not|No\",\"[6XK] Retrovisores plegables\",\"Incluye:\",\"[6XE] Retrovisores plegables\",\"If:|Si:\",\"Not|No\",\"[PXC] Faros Matrix LED con in\",\"Incluye:\",\"[PX2] Faros completos en tecn\"],\"specsDatabaseName\":\"SSCE_CS2002\",\"includes\":[1033,1045,1025],\"requires\":[],\"excludes\":[],\"ifNotbuiltRequiredInfo\":[],\"ifBuiltRequires\":[],\"priceChanges\":[],\"discountOptionInfos\":[],\"excludedByIncludingOptionInfos\":[],\"includesOptions\":null,\"requiresOptions\":null,\"excludesOptions\":null,\"ifNotBuiltOptions\":null,\"priceChangeOptions\":null,\"discountOptions\":null,\"retailPrice902\":1266.29,\"basePrice903\":1046.52,\"countryPrice904\":null,\"countryPrice905\":null,\"retailPriceWithDelivery906\":null,\"categoryName\":\"Others\",\"translatedCategoryName\":null,\"optionTypeName\":\"Packages\",\"price\":1266.29,\"priceChange\":null,\"displayPrice\":\"\u20ac1,266\",\"startDate\":null,\"endDate\":null,\"optionState\":0,\"unbuild\":false,\"requiredBy\":[],\"includedBy\":[],\"changedPricedOptionIds\":[],\"changingChangedOptionInfos\":[],\"removedRequiredOptions\":null,\"optionCode\":\"WAV\",\"builtByCompareEquip\":false,\"schemaId\":0}}", json_encode($actual["options"]["packs"]));
    }
}
