<?php

declare(strict_types=1);

namespace App\Modules\Scraper\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartAllScrapeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pages' => ['sometimes', 'integer', 'min:1', 'max:'.config('scraper.absolute_max_pages')],
        ];
    }
}
