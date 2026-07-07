<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$examples = [
    'creational/factory-method.php',
    'creational/abstract-factory.php',
    'creational/builder.php',
    'creational/singleton.php',
    'creational/prototype.php',
    'structural/adapter.php',
    'structural/decorator.php',
    'structural/facade.php',
    'structural/proxy.php',
    'structural/composite.php',
    'structural/bridge.php',
    'structural/flyweight.php',
    'behavioral/strategy.php',
    'behavioral/observer.php',
    'behavioral/command.php',
    'behavioral/chain-of-responsibility.php',
    'behavioral/template-method.php',
    'behavioral/state.php',
    'behavioral/iterator.php',
    'behavioral/mediator.php',
    'behavioral/specification.php',
    'architectural/repository.php',
    'architectural/service-layer.php',
    'architectural/action-class.php',
    'architectural/dto.php',
    'architectural/value-object.php',
    'architectural/domain-event.php',
    'architectural/query-object.php',
];

foreach ($examples as $example) {
    echo PHP_EOL . "### {$example}" . PHP_EOL;
    require __DIR__ . '/' . $example;
}
