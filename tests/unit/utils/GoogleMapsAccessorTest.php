<?php
use AppBundle\Utils\GoogleMapsAccessor;
use AppBundle\Utils\Point;

/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 25/08/16
 * Time: 11:06
 */
class GoogleMapsAccessorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GoogleMapsAccessor
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new GoogleMapsAccessor();
    }

    public function test_getPositionFromZipCode_willReturnCorrectPosition()
    {
        $actual = $this->sut->getPositionFromZipCode("08030");
        $this->assertEquals(new Point(2.196618, 41.437868899999998), $actual["position"]);
        $this->assertEquals("Barcelona", $actual["city"]);
    }

    public function provideDataForDistance()
    {
        return array(
            array(41.437869,2.196618,41.437869,2.196618, 0),
            array(38.898556,-77.037852,38.897147,-77.043934, 0.54915579120356),
            array(30.898556,-77.037852,38.897147,-77.043934, 889.40291177309),
        );
    }

    /**
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @param $expect
     * @dataProvider provideDataForDistance
     */
    public function test_getDistanceShouldReturnCorrectNumber($lat1, $lng1, $lat2, $lng2, $expect)
    {
        $actual = $this->sut->distance($lat1, $lng1, $lat2, $lng2);
        $this->assertEquals($expect, $actual);
    }
}