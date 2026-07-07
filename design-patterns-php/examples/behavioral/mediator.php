<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';
foreach (DesignPatterns\Behavioral\Mediator::run() as $line) echo $line . PHP_EOL;
