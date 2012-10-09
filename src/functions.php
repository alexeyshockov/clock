<?php

use Clock\DateTime;

/**
 * Shortest way to create new DateTime object.
 *
 * @param null|string|\DateTime $dt
 */
function clock($dt = null)
{
    return new DateTime($dt);
}
