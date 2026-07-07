<?php

declare(strict_types=1);

$examples = [
    '01-single-responsibility.php',
    '02-open-closed.php',
    '03-liskov-substitution.php',
    '04-interface-segregation.php',
    '05-dependency-inversion.php',
];

foreach ($examples as $example) {
    echo PHP_EOL . "### {$example}" . PHP_EOL;
    require __DIR__ . '/' . $example;
}
