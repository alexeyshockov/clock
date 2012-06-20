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
        $dt = new \Clock\DateTime('2012-01-01T12:12:12+04:00');

        assertSame('2012-01-01T12:12:12+04:00', $dt->__toString());
    }
}
