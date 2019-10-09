<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

include 'TestDTO.php';
include 'TestDTOMother.php';
include 'TestDTOCollection.php';
include 'CollectionMother.php';

$mother = new TestDTOMother;
$mother->bar = ['x', 'y'];
$mother->baz = 'here we go';
$mother->build()->dump();

TestDTOMother::predefined()
    ->bar(['value', 'other'])
    ->foo(3)
    ->date(new DateTimeImmutable('-3 days'))
    ->build()
    ->dump();

(new TestDTOMother)
    ->build()
    ->dump();

CollectionMother::withElements()->dump();

