<?php

declare(strict_types=1);

use App\Modules\Scraper\Exports\Concerns\CalculatesMedian;

function medianCalculator()
{
    return new class
    {
        use CalculatesMedian;

        public function calculate(array $values): float
        {
            return $this->median($values);
        }
    };
}

test('it returns the middle value for an odd number of values', function () {
    expect(medianCalculator()->calculate([300, 100, 200]))->toBe(200.0);
});

test('it averages the two middle values for an even number of values', function () {
    expect(medianCalculator()->calculate([400, 100, 300, 200]))->toBe(250.0);
});

test('it returns the value itself when given a single value', function () {
    expect(medianCalculator()->calculate([42]))->toBe(42.0);
});
