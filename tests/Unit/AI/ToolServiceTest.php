<?php

declare(strict_types=1);

use App\Modules\AI\Services\ToolService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\TextResponseFake;
use Prism\Prism\Tool;
use Prism\Prism\ValueObjects\Usage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new ToolService;
});

test('registerTool adds tool and returns self for chaining', function () {
    $tool = (new Tool)
        ->as('get_district_stats')
        ->for('Get stats for a district')
        ->withStringParameter('district', 'District name')
        ->using(fn (string $district): string => json_encode(['district' => $district, 'median' => 2055]));

    $result = $this->service->registerTool($tool);

    expect($result)->toBeInstanceOf(ToolService::class);
});

test('run returns AI text response', function () {
    Prism::fake([
        TextResponseFake::make()
            ->withText('The median price in Kentron is $2,500/sqm')
            ->withUsage(new Usage(10, 20)),
    ]);

    $result = $this->service->run('What is the median price in Kentron?');

    expect($result)->toBe('The median price in Kentron is $2,500/sqm');
});
