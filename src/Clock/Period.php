<?php

namespace Clock;

/**
 * Date & time period. Immutable.
 *
 * @author Alexey Shockov <alexey@shockov.com>
 */
class Period extends \DatePeriod
{
    /**
     * @var \Clock\DateTime
     */
    private $start;

    /**
     * @var \Clock\DateTime
     */
    private $end;

    /**
     * @var \Clock\Interval
     */
    private $interval;

    /**
     * Params are equal to original constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $args = func_get_args();

        if (in_array(func_num_args(), array(3, 4))) {
            $this->interval = new Interval($args[1]);
        }

        if (in_array(func_num_args(), array(1, 2))) {
            $this->interval = $this->getIntervalFromString($args[0]);
        }

        call_user_func_array(array('parent', '__construct'), func_get_args());
    }

    /**
     * @todo Examples.
     *
     * Determines interval from ISO formatted string period string.
     *
     * @param string $string
     *
     * @return \DateInterval
     */
    private function getIntervalFromString($string)
    {
        $parts = explode('/', $string);

        foreach ($parts as $part) {
            if (0 === strpos($part, 'P')) {
                return new Interval($part);
            }
        }

        throw new \InvalidArgumentException('Unable to get interval from string.');
    }

    /**
     * @todo Determine from constructor.
     *
     * @return \Clock\DateTime
     */
    public function getStart()
    {
        if (!$this->start) {
            foreach ($this as $start) {
                $this->start = new \Clock\DateTime($start);

                break;
            }
        }

        return $this->start;
    }

    /**
     * @todo Determine from constructor.
     *
     * @return \Clock\DateTime
     */
    public function getEnd()
    {
        if (!$this->end) {
            $end = null;
            foreach ($this as $end) {

            }

            $this->end = new \Clock\DateTime($end);
        }

        return $this->end;
    }

    /**
     * @return \Clock\Interval
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param \DateTime $dt
     *
     * @return bool
     */
    public function contains(\DateTime $dt)
    {
        return (($dt >= $this->getStart()) && ($dt <= $this->getEnd()));
    }

    /**
     * @param \DatePeriod $dp
     *
     * @return bool
     */
    public function isPartOf(\DatePeriod $dp)
    {
        // FIXME Implement.
    }

    /**
     * @param \DatePeriod $dp
     *
     * @return bool
     */
    public function isIntersectedWith(\DatePeriod $dp)
    {
        // FIXME Implement.
    }

    /**
     * @todo Examples.
     *
     * ISO period string.
     *
     * @return string
     */
    public function __toString()
    {
        $string = $this->getStart()->__toString().'/'
            .$this->getInterval()->__toString().'/'
            .$this->getEnd()->__toString();

        // TODO Replace this shit...
        return str_replace('+00:00', 'Z', $string);
    }

    /**
     * By default — for current month.
     *
     * @param \DateTime|null $month
     */
    public static function forMonth(\DateTime $month = null)
    {
        if (!$month) {
            $month = new \DateTime();
        }

        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $month->format('Y-m-01 00:00:00'));
        // Дата окончания включается исключительно, нужно сделать дополнительный день, чтобы получить её в периоде.
        $endDate   = \DateTime::createFromFormat('Y-m-d H:i:s', $month->format('Y-m-t 00:00:00'))->modify('+1 day');

        return new static($startDate, new \DateInterval('P1D'), $endDate);
    }

    /**
     * By default — for current week.
     *
     * @param \DateTime|null $monday
     */
    public static function forWeek(\DateTime $monday = null)
    {
        if (!$monday) {
            $currentDay = new \DateTime();
            $monday     = $currentDay->modify('-'.($currentDay->format('N') - 1).' day');
        }

        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $monday->format('Y-m-d 00:00:00'));
        $endDate   = clone $startDate;
        // We must add one more day to include end date in period.
        $endDate   = $endDate->modify('+6 days')->modify('+1 day');

        return new static($startDate, new \DateInterval('P1D'), $endDate);
    }
}
