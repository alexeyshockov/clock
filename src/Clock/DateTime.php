<?php

namespace Clock;

/**
 * @author Alexey Shockov <alexey@shockov.com>
 *
 * @see http://www.date4j.net/javadoc/index.html
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    private $millisecond = 0;

    private function normalizeDateTimeString($string)
    {
        // Milliseconds.
        $pattern = '#\.(\d{1,})#';
        preg_match($pattern, $string, $matches);
        if ($matches) {
            // Clear milliseconds.
            $this->setMillisecond($matches[1]);
            $string = preg_replace('#\.\d{1,}#', '', $string);
        }

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
     *
     * @see http://www.w3.org/TR/NOTE-datetime
     * @see \Clock\DateTime::toIsoString()
     *
     * @throws \InvalidArgumentException When date and time format is wrong.
     *
     * @param null|string|\DateTime $dt
     * @param null|\DateTimeZone    $tz
     */
    public function __construct($dt = null, \DateTimeZone $tz = null)
    {
        if (!is_null($dt)) {
            if ($dt instanceof \DateTime) {
                $tz = $dt->getTimezone();
                $dt = $dt->format(static::ATOM);
            } elseif (is_string($dt)) {
                $dt = $this->normalizeDateTimeString($dt);
            } elseif (is_int($dt)) {
                // Timestamp.
                $dt = '@'.$dt;
            } elseif (is_float($dt)) {
                $this->setMillisecond($this->getMillisecondsFromTimestamp($dt));
                $dt = '@'.floor($dt);
            } else {
                throw new \InvalidArgumentException('Wrong argument type.');
            }
        }

        try {
            parent::__construct($dt, $tz);
            if (is_null($dt)) {
                $this->setMillisecond($this->getMillisecondsFromTimestamp(microtime(true)));
            }
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('Wrong date and time format.', 0, $exception);
        }
    }

    private function getMillisecondsFromTimestamp($timestamp)
    {
        $milliseconds = 0;
        if (is_float($timestamp)) {
            $milliseconds = floor(($timestamp - floor($timestamp)) * 1000);
        }

        return $milliseconds;
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
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->toIsoString();
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @param \DateInterval $interval
     *
     * @return \Clock\DateTime
     */
    public function add($interval)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @param \DateInterval $interval
     *
     * @return \Clock\DateTime
     */
    public function sub($interval)
    {
        $dt = clone $this;

        $dt->callOriginal(__FUNCTION__, func_get_args());

        return $dt;
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
            parent::diff($dt, $absolute)
        );
    }

    /**
     * Complete date plus hours, minutes, seconds and milliseconds in UTC timezone:
     * <code>
     * 1997-07-16T19:20:30Z
     * </code>
     *
     * @see \Clock\DateTime::fromIsoString()
     *
     * @param bool $withMilliseconds
     *
     * @return string
     */
    public function toIsoString($withMilliseconds = false)
    {
        $utcDateTime = $this->setTimezone(new \DateTimeZone('UTC'));
        $formattedTime = str_replace('+00:00', 'Z', $utcDateTime->format(static::ATOM));
        if ($withMilliseconds) {
            $milliseconds = str_pad($this->getMillisecond(), 3, '0', STR_PAD_LEFT);
            $formattedTime = str_replace('Z', '.'.$milliseconds.'Z', $formattedTime);
        }

        return $formattedTime;
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

    public function setMillisecond($millisecond)
    {
        $this->millisecond = $millisecond;
    }

    public function isEqualTo($dt)
    {
        if ($dt instanceof \DateTime) {
            return !$this->compareTo($dt);
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
        return (bool) $this->format('L');
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

    public function getMillisecond()
    {
        return $this->millisecond;
    }

    public function __toString()
    {
        return $this->toIsoString();
    }
}
