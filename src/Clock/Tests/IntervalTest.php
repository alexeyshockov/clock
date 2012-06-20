<?php

namespace Clock\Tests;

require_once "PHPUnit/Framework/Assert/Functions.php";

/**
 * @author Alexey Shockov <alexey@shockov.com>
 */
class IntervalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldBeCorrectlyFormattedToString()
    {
        $di = new \Clock\Interval('P1Y');

        assertSame('P1Y0M0DT0H0M0S', $di->__toString());
    }
}
