<?php

namespace Clock;

/**
 * @author Alexey Shockov <alexey@shockov.com>
 *
 * @see http://www.date4j.net/javadoc/index.html
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    private function normalizeDateTimeString($string)
    {
        // Clear milliseconds.
        $string = preg_replace('#\.\d{1,}#', '', $string);
        $string = str_replace('Z', '+00:00', $string);

        return $string;
    }

    /**
     * ISO 8601 supported. Complete date plus hours, minutes and seconds:
     * <code>
     * YYYY-MM-DDThh:mm:ssTZD (eg. 1997-07-16T19:20:30+01:00)
     * </code>
     *
     * Where:
     * <code>
     * YYYY = four-digit year
     * MM   = two-digit month (01 = January, etc.)
     * DD   = two-digit day of month (01 through 31)
     * hh   = two digits of hour (00 through 23) (am/pm not allowed)
     * mm   = two digits of minute (00 through 59)
     * ss   = two digits of second (00 through 59)
     * TZD  = time zone designator (Z or +hh:mm or -hh:mm)
     * </code>
     *
     * PHP not support milliseconds. If your string include it, they will be ignored (actually for JavaScript's
     * Date.toISOString() users).
     *
     *
     * @see http://www.w3.org/TR/NOTE-datetime
     * @see \Clock\DateTime::toIsoString()
     *
     * @throws \InvalidArgumentException When date and time format is wrong.
     *
     * @param string|\DateTime $dt
     * @param \DateTimeZone    $tz
     */
    public function __construct($dt, \DateTimeZone $tz = null)
    {
        if ($dt instanceof \DateTime) {
            $tz = $dt->getTimezone();
            $dt = $dt->format(static::ATOM);
        } elseif (is_scalar($dt)) {
            $dt = $this->normalizeDateTimeString($dt);
        } else {
            throw new \InvalidArgumentException('Wrong argument type.');
        }

        try {
            parent::__construct($dt, $tz);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('Wrong date and time format.', 0, $exception);
        }
    }

    public static function forToday()
    {
        return static::forDate(date('Y'), date('m'), date('d'));
    }

    public static function forDate($year, $month, $day)
    {
        $dt = new \DateTime();
        $dt->setDate($year, $month, $day);
        $dt->setTime(0, 0, 0);

        return new static($dt);
    }

    public static function forTimestamp($timestamp)
    {
        $dt = new \DateTime();
        $dt->setTimestamp($timestamp);

        return new static($dt);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toIsoString();
    }

    /**
     * @param DateTime $dt
     * @param bool $absolute
     *
     * @return \Clock\Interval
     */
    public function diff($dt, $absolute = null)
    {
        return new Interval(
            $this->diff($dt, $absolute)
        );
    }

    /**
     * Complete date plus hours, minutes and seconds in UTC timezone:
     * <code>
     * 1997-07-16T19:20:30Z
     * </code>
     *
     * P.S. PHP not support milliseconds.
     *
     * @see \Clock\DateTime::fromIsoString()
     *
     * @return string
     */
    public function toIsoString()
    {
        $utcDateTime = $this->setTimezone(new \DateTimeZone('UTC'));

        return str_replace('+00:00', 'Z', $utcDateTime->format(static::ATOM));
    }

    private function callOriginal($method, $arguments)
    {
        return call_user_func_array(array('parent', $method), $arguments);
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function modify($modifier)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * @param \DateTimeZone $tz
     *
     * @return \Clock\DateTime
     */
    public function setTimezone($tz)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setTime($hour, $minute, $second = null)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setDate($year, $month, $day)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setISODate($year, $week, $day = 1)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setTimestamp($timestamp)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    public function isEqualTo($dt)
    {
        if ($dt instanceof \DateTime) {
            return (bool) $this->compareTo($dt);
        }

        return false;
    }

    public function compareTo(\DateTime $dt)
    {
        if ($this == $dt) {
            return 0;
        }

        return ($this > $dt ? 1 : -1);
    }

    public function isLeapYear()
    {
        // FIXME Implement.
    }

    public function isInTheFuture()
    {
        return (1 == $this->compareTo(new \DateTime()));
    }

    public function getYear()
    {
        return $this->format('Y');
    }

    public function getMonth()
    {
        return $this->format('m');
    }

    public function getDay()
    {
        return $this->format('d');
    }

    public function getDayOfYear()
    {
        return $this->format('z');
    }

    public function getDayOfWeek()
    {
        return $this->format('N');
    }

    public function getHour()
    {
        return $this->format('H');
    }

    public function getMinute()
    {
        return $this->format('i');
    }

    public function getSecond()
    {
        return $this->format('s');
    }

    public function __toString()
    {
        return $this->toIsoString();
    }
}
