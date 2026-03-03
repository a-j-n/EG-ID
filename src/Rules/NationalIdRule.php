<?php

declare(strict_types=1);

namespace EgyptianNationalId\Rules;

use EgyptianNationalId\NationalIdService;
use Illuminate\Contracts\Validation\ValidationRule;

class NationalIdRule implements ValidationRule
{
    public function __construct(private readonly ?NationalIdService $service = null)
    {
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $service = $this->service ?? new NationalIdService();

        if (!$service->validate($value)) {
            $fail('The :attribute field must be a valid Egyptian national ID.');
        }
    }
}
