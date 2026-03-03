# Egyptian National ID Laravel Package

PHP and Laravel package for Egyptian National ID:

- validation
- parsing
- generation
- sanitization (including Arabic numerals)

## Credits

This package is a PHP/Laravel adaptation of the original project by Mahmoud Ebeid (`mahmoudEbeid2`):
https://github.com/mahmoudEbeid2/egyptian-national-id

## Installation

```bash
composer require mahmoudebeid2/egyptian-national-id-laravel
```

Laravel package discovery is enabled automatically.

## Quick Usage

```php
use EgyptianNationalId\NationalIdService;

$service = app(NationalIdService::class);

$isValid = $service->validate('30001010123456');
$id = $service->generate(['gender' => 'Male']);
$parsed = $service->parse($id);
```

## Facade Usage

```php
use EgyptianNationalId\Facades\NationalId;

$isValid = NationalId::validate('30001010123456');
$parsed = NationalId::parse('30001010123456');
```

## Validation Rule (String)

```php
$request->validate([
    'national_id' => ['required', 'national_id'],
]);
```

## Validation Rule (Class)

```php
use EgyptianNationalId\Rules\NationalIdRule;

$request->validate([
    'national_id' => ['required', new NationalIdRule()],
]);
```

## Parse Result Shape

```php
[
  'national_id' => '...',
  'birth_date' => DateTimeImmutable,
  'birth_year' => 1998,
  'birth_month' => 7,
  'birth_day' => 21,
  'age' => 27,
  'gender' => 'Male'|'Female',
  'governorate' => ['code' => 1, 'name_en' => 'Cairo', 'name_ar' => 'Al Qahirah', 'region' => 'Cairo'],
  'region' => 'Cairo',
  'inside_egypt' => true,
  'is_adult' => true
]
```

## API

- `sanitize(string|int|null $id): string`
- `validate(string|int|null $id): bool`
- `parse(string|int|null $id): array`
- `extractBirthDate(string|int|null $id): DateTimeImmutable`
- `extractGender(string|int|null $id): string`
- `extractGovernorate(string|int|null $id): ?array`
- `calculateAge(DateTimeInterface $birthDate, ?DateTimeInterface $at = null): int`
- `generate(array $options = []): string`

### Generate Options

- `birth_year` (int)
- `birth_month` (int)
- `birth_day` (int)
- `governorate_code` (int)
- `gender` (`Male` or `Female`)

## License

MIT
