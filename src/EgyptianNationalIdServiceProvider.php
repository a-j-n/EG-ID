<?php

declare(strict_types=1);

namespace EgyptianNationalId;

use EgyptianNationalId\Rules\NationalIdRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class EgyptianNationalIdServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NationalIdService::class, fn (): NationalIdService => new NationalIdService());
        $this->app->alias(NationalIdService::class, 'national-id');
    }

    public function boot(): void
    {
        Validator::extend('national_id', function (string $attribute, mixed $value): bool {
            return $this->app->make(NationalIdService::class)->validate($value);
        });

        Validator::replacer('national_id', function (): string {
            return 'The :attribute field must be a valid Egyptian national ID.';
        });
    }
}
