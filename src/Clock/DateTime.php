<?php

namespace Clock;

/**
 * @author Alexey Shockov <alexey@shockov.com>
 *
 * @see http://www.date4j.net/javadoc/index.html
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    /**
     * @param string|\DateTime $dt
     */
    public function __construct($dt)
    {
        if ($dt instanceof \DateTime) {
            $dt = $dt->format(static::ATOM);
        }

        parent::__construct($dt);
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
        return $this->format(static::ISO8601);
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
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function modify($modifier)
    {
        $dt = clone $this;

        return $dt->modify($modifier);
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setTime($hour, $minute, $second = null)
    {
        $dt = clone $this;

        return $dt->setTime($hour, $minute, $second);
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setDate($year, $month, $day)
    {
        $dt = clone $this;

        return $dt->setDate($year, $month, $day);
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setISODate($year, $week, $day = 1)
    {
        $dt = clone $this;

        return $dt->setISODate($year, $week, $day);
    }

    /**
     * Breaks BC for original \DateTime. Immutable.
     *
     * @return \Clock\DateTime
     */
    public function setTimestamp($timestamp)
    {
        $dt = clone $this;

        return $dt->setTimestamp($timestamp);
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
        return $this->format(static::ATOM);
    }
}
