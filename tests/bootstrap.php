<?php

declare(strict_types=1);

namespace Api\Tests;

use Tester\Environment;

require __DIR__ . '/../vendor/autoload.php';

define('TEST_DOCU_DIR', __DIR__ . '/testDir');

Environment::setup();

date_default_timezone_set('Europe/Prague');
