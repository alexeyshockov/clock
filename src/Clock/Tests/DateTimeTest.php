<?php

namespace Clock\Tests;

require_once "PHPUnit/Framework/Assert/Functions.php";

/**
 * @author Alexey Shockov <alexey@shockov.com>
 */
class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldBeCorrectlyFormattedToString()
    {
        $dt1 = new \Clock\DateTime('2012-01-01T12:12:12+04:00');
        // Z is +00:00.
        $dt2 = new \Clock\DateTime('2012-01-01T12:12:12Z');
        // First time zone more important.
        $dt3 = new \Clock\DateTime('2012-01-01T12:12:12+02:00', new \DateTimeZone('Europe/Moscow'));
        // ISO 8601 with milliseconds... Ignore milliseconds.
        $dt4 = new \Clock\DateTime('2012-08-16T09:38:14.451Z');
        // With milliseconds with leading zeros.
        $dt5 = new \Clock\DateTime('2012-01-01T12:12:12Z');
        $dt5->setMillisecond(10);
        // ISO8601 without some parts. 00 will be assumed for it (default \DateTime behaviour).
        $dt6 = new \Clock\DateTime('2012-01-01T12:02');
        $offset = date('Z') / 3600;

        assertSame('2012-01-01T08:12:12Z', $dt1->__toString());
        assertSame('2012-01-01T12:12:12Z', $dt2->__toString());
        assertSame('2012-01-01T10:12:12Z', $dt3->__toString());
        assertSame('2012-08-16T09:38:14Z', $dt4->__toString());
        assertSame('2012-01-01T12:12:12.010Z', $dt5->toIsoString(true));
        assertSame('2012-01-01T'.sprintf('%02d', 12 - $offset).':02:00Z', $dt6->__toString());
    }

    /**
     * @test
     */
    public function setTimeShouldBeImmutable()
    {
        $dt = new \Clock\DateTime('2012-01-01T12:12:12+04:00');

        $modifiedDt = $dt->setTime(0, 0);

        assertSame(1325405532, $dt->getTimestamp());
        assertSame(1325361600, $modifiedDt->getTimestamp());
    }

    /**
     * @test
     */
    public function toIsoStringShouldBeWithMillisconds()
    {
        $dt = new \Clock\DateTime('2012-08-16T09:38:14.451Z');

        assertSame('2012-08-16T09:38:14.451Z', $dt->toIsoString(true));
    }
}
