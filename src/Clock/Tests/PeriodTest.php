<?php

namespace Clock\Tests;

require_once "PHPUnit/Framework/Assert/Functions.php";

/**
 * @author Alexey Shockov <alexey@shockov.com>
 */
class PeriodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function iteratorShouldWorkCorrectly()
    {
        $dp = new \Clock\Period('R5/2008-03-01T13:00:00Z/P1Y2M10DT2H30M');

        $dates = array();

        foreach ($dp as $dt) $dates[] = $dt;

        assertEquals(array(
            new \DateTime('2008-03-01T13:00:00+00:00'),
            new \DateTime('2009-05-11T15:30:00+00:00'),
            new \DateTime('2010-07-21T18:00:00+00:00'),
            new \DateTime('2011-10-01T20:30:00+00:00'),
            new \DateTime('2012-12-11T23:00:00+00:00'),
            new \DateTime('2014-02-22T01:30:00+00:00'),
        ), $dates);
    }

    /**
     * @test
     */
    public function shouldBeCorrectlyFormattedToString()
    {
        $dp = new \Clock\Period('R5/2008-03-01T13:00:00Z/P1Y2M10DT2H30M');

        assertSame('2008-03-01T13:00:00Z/P1Y2M10DT2H30M0S/2014-02-22T01:30:00Z', $dp->__toString());
    }
}
