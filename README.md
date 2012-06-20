# Clock [![Build Status](https://secure.travis-ci.org/alexeyshockov/clock.png)](http://travis-ci.org/alexeyshockov/clock)

## Goal

_Convenient_ and _safe_ way to work with date & time in PHP.

## Benefits

* Rich, easy to understand (and remember) method set.
* Functional idioms:
    * immutable collections (safest and usable for most cases).

## Installation

Clock currently may be installed as submodule for your Git project:

``` bash
git submodule add git://github.com/alexeyshockov/clock.git vendor/clock
```

or throught [Composer](https://github.com/composer/composer):

``` json
{
    "require": {
        "alexeyshockov/clock": "dev-master"
    }
}
```

## Usage

Some examples:

``` php
<?php

$period = new \DatePeriod('R5/2008-03-01T13:00:00Z/P1Y2M10DT2H30M');

// Converting period to collection of dates with Colada.
$dates = to_collection($period);

$formattedDates = $dates->mapBy(x()->format(\DateTime::ATOM));
```
