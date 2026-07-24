<?php

declare(strict_types=1);

test('has_bill_of_sale maps true correctly from array', function () {
    $dto = makeListing(['has_bill_of_sale' => true]);
    expect($dto->hasBillOfSale)->toBeTrue();
});

test('has_bill_of_sale is null when not provided', function () {
    $dto = makeListing([]);
    expect($dto->hasBillOfSale)->toBeNull();
});

test('has_bill_of_sale maps false correctly', function () {
    $dto = makeListing(['has_bill_of_sale' => false]);
    expect($dto->hasBillOfSale)->toBeFalse();
});

test('has_bill_of_sale is included in toArray output', function () {
    $dto = makeListing(['has_bill_of_sale' => true]);
    expect($dto->toArray())->toHaveKey('has_bill_of_sale');
});
