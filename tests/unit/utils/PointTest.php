<?php

use AppBundle\Utils\Point;

class PointTest extends PHPUnit_Framework_TestCase
{
    const TEST_KM = 15;
    /**
     * @var Point
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new Point(2.196618, 41.4378689);
    }

    public function test_getSquareCoordinates_withGivenDistance_willReturnCorrectPoints()
    {
        $actual = $this->sut->getSquareCoordinates(self::TEST_KM);
        $this->assertEquals(array(
            new Point(2.0164320998032, 41.302788247826),
            new Point(2.3768039001968, 41.572949552174)
        ), $actual);
    }
}