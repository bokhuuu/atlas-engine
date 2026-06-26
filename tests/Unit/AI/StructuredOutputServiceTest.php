<?php

declare(strict_types=1);

use App\Modules\AI\Services\StructuredOutputService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\Testing\StructuredResponseFake;
use Prism\Prism\ValueObjects\Usage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new StructuredOutputService;
});

test('extract returns structured array matching schema', function () {
    Prism::fake([
        StructuredResponseFake::make()
            ->withStructured([
                'district' => 'Kentron',
                'price' => 150000,
                'area' => 85,
                'rooms' => 3,
            ])
            ->withUsage(new Usage(10, 20)),
    ]);

    $schema = new ObjectSchema(
        name: 'listing',
        description: 'A real estate listing',
        properties: [
            new StringSchema('district', 'The district'),
            new NumberSchema('price', 'The price in USD'),
            new NumberSchema('area', 'The area in sqm'),
            new NumberSchema('rooms', 'Number of rooms'),
        ],
        requiredFields: ['district', 'price', 'area', 'rooms']
    );

    $result = $this->service->extract(
        '3-bedroom apartment in Kentron, 85sqm, $150,000',
        $schema
    );

    expect($result)->toBeArray();
    expect($result['district'])->toBe('Kentron');
    expect($result['price'])->toBe(150000);
    expect($result['area'])->toBe(85);
    expect($result['rooms'])->toBe(3);
});
