<?php

namespace Clock;

/**
 * Immutable.
 *
 * @author Alexey Shockov <alexey@shockov.com>
 */
class Interval extends \DateInterval
{
    /**
     * @param string|\DateInterval $spec
     */
    public function __construct($spec)
    {
        if ($spec instanceof \DateInterval) {
            $spec = $this->format2iso($spec);
        }

        parent::__construct($spec);
    }

    /**
     * ISO 8601 representation. "P3Y6M4DT12H30M5S", by example.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format2iso($this);
    }

    private function format2iso(\DateInterval $di)
    {
        return 'P'.
            $di->format('%y').'Y'.
            $di->format('%m').'M'.
            $di->format('%d').'D'.
            'T'.
            $di->format('%h').'H'.
            $di->format('%i').'M'.
            $di->format('%s').'S';
    }

    /**
     * @return int
     */
    public function toSeconds()
    {
        return ($this->y * 365 * 24 * 60 * 60) +
            ($this->m * 30 * 24 * 60 * 60) +
            ($this->d * 24 * 60 * 60) +
            ($this->h * 60 * 60) +
            ($this->i * 60) +
            $this->s;
    }
}
