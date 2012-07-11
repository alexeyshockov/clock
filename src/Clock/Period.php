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
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
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
        // FIXME Implement.
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
     * ISO period string.
     *
     * @return string
     */
    public function __toString()
    {
        // FIXME Implement.
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
        // Дата окончания включается исключительно, нужно сделать дополнительный день, чтобы получить её в периоде.
        $endDate   = $endDate->modify('+6 days')->modify('+1 day');

        return new static($startDate, new \DateInterval('P1D'), $endDate);
    }
}
