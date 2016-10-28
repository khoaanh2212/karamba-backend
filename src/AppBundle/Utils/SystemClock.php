<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 8/07/16
 * Time: 16:45
 */

namespace AppBundle\Utils;




class CurrentTime
{
    /**
     * @var \DateTime
     */
    private $now;

    public function __construct(\DateTime $now = null)
    {
        if(!$now) {
            $now = new \DateTime();
        }
        $this->now = $now;
    }

    public function getDate()
    {
        return $this->now;
    }

    public function getDealerApplicationExpirationDate(): \DateTime
    {
        return $this->now->modify('+1 week');
    }

    public function isExpirationDateWithinTime(\DateTime $expirationDate)
    {
        return $this->now <= $expirationDate;
    }
}

class SystemClock
{
    /**
     * @var \DateTime
     */
    private static $innerDate;

    public static function setInnerDate(\DateTime $date)
    {
        self::$innerDate = $date;
    }

    public static function now(): CurrentTime
    {
        return new CurrentTime(self::$innerDate);
    }
}