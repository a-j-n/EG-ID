<?php

declare(strict_types=1);

namespace EgyptianNationalId;

use DateTimeImmutable;
use DateTimeInterface;
use EgyptianNationalId\Support\Governorates;
use InvalidArgumentException;

class NationalIdService
{
    private const CHECKSUM_MULTIPLIERS = [2, 7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
    private const ARABIC_TO_WESTERN = [
        '٠' => '0',
        '١' => '1',
        '٢' => '2',
        '٣' => '3',
        '٤' => '4',
        '٥' => '5',
        '٦' => '6',
        '٧' => '7',
        '٨' => '8',
        '٩' => '9',
    ];

    public function sanitize(string|int|null $nationalId): string
    {
        if ($nationalId === null) {
            return '';
        }

        $id = (string) $nationalId;
        $id = strtr($id, self::ARABIC_TO_WESTERN);

        return preg_replace('/\D/u', '', $id) ?? '';
    }

    public function validate(string|int|null $nationalId): bool
    {
        $id = $this->sanitize($nationalId);

        if (!preg_match('/^\d{14}$/', $id)) {
            return false;
        }

        $centuryDigit = (int) $id[0];
        if ($centuryDigit !== 2 && $centuryDigit !== 3) {
            return false;
        }

        $year = (int) substr($id, 1, 2);
        $month = (int) substr($id, 3, 2);
        $day = (int) substr($id, 5, 2);
        $fullYear = ($centuryDigit === 2 ? 1900 : 2000) + $year;

        if (!checkdate($month, $day, $fullYear)) {
            return false;
        }

        $birthDate = new DateTimeImmutable(sprintf('%04d-%02d-%02d', $fullYear, $month, $day));
        if ($birthDate > new DateTimeImmutable('today')) {
            return false;
        }

        $govCode = (int) substr($id, 7, 2);
        if (!isset(Governorates::all()[$govCode])) {
            return false;
        }

        return (int) $id[13] === $this->calculateCheckDigit(substr($id, 0, 13));
    }

    /**
     * @return array{
     *  national_id:string,
     *  birth_date:DateTimeImmutable,
     *  birth_year:int,
     *  birth_month:int,
     *  birth_day:int,
     *  age:int,
     *  gender:string,
     *  governorate:array{code:int,name_en:string,name_ar:string,region:string}|null,
     *  region:string,
     *  inside_egypt:bool,
     *  is_adult:bool
     * }
     */
    public function parse(string|int|null $nationalId): array
    {
        $id = $this->sanitize($nationalId);
        if (!$this->validate($id)) {
            throw new InvalidArgumentException('Invalid national ID.');
        }

        $birthDate = $this->extractBirthDate($id);
        $governorate = $this->extractGovernorate($id);
        $age = $this->calculateAge($birthDate);
        $region = $governorate['region'] ?? 'Foreign';
        $insideEgypt = ($governorate['code'] ?? 88) !== 88;

        return [
            'national_id' => $id,
            'birth_date' => $birthDate,
            'birth_year' => (int) $birthDate->format('Y'),
            'birth_month' => (int) $birthDate->format('m'),
            'birth_day' => (int) $birthDate->format('d'),
            'age' => $age,
            'gender' => $this->extractGender($id),
            'governorate' => $governorate,
            'region' => $region,
            'inside_egypt' => $insideEgypt,
            'is_adult' => $age >= 18,
        ];
    }

    public function extractBirthDate(string|int|null $nationalId): DateTimeImmutable
    {
        $id = $this->sanitize($nationalId);
        if (strlen($id) < 7) {
            throw new InvalidArgumentException('National ID is too short.');
        }

        $centuryDigit = (int) $id[0];
        $year = (int) substr($id, 1, 2);
        $month = (int) substr($id, 3, 2);
        $day = (int) substr($id, 5, 2);
        $fullYear = ($centuryDigit === 2 ? 1900 : 2000) + $year;

        if (!checkdate($month, $day, $fullYear)) {
            throw new InvalidArgumentException('Invalid birth date in national ID.');
        }

        return new DateTimeImmutable(sprintf('%04d-%02d-%02d', $fullYear, $month, $day));
    }

    public function extractGender(string|int|null $nationalId): string
    {
        $id = $this->sanitize($nationalId);
        if (strlen($id) < 13) {
            throw new InvalidArgumentException('National ID is too short.');
        }

        return ((int) $id[12] % 2 === 0) ? 'Female' : 'Male';
    }

    /**
     * @return array{code:int,name_en:string,name_ar:string,region:string}|null
     */
    public function extractGovernorate(string|int|null $nationalId): ?array
    {
        $id = $this->sanitize($nationalId);
        if (strlen($id) < 9) {
            throw new InvalidArgumentException('National ID is too short.');
        }

        $code = (int) substr($id, 7, 2);
        $governorates = Governorates::all();

        return $governorates[$code] ?? null;
    }

    public function calculateAge(DateTimeInterface $birthDate, ?DateTimeInterface $at = null): int
    {
        $atDate = $at ?? new DateTimeImmutable('today');
        return (int) $birthDate->diff($atDate)->y;
    }

    /**
     * @param  array{
     *  birth_year?:int,
     *  birth_month?:int,
     *  birth_day?:int,
     *  governorate_code?:int,
     *  gender?:string
     * } $options
     */
    public function generate(array $options = []): string
    {
        $currentYear = (int) date('Y');
        $year = $options['birth_year'] ?? ($currentYear - 25);
        $month = $options['birth_month'] ?? random_int(1, 12);
        $day = $options['birth_day'] ?? random_int(1, 28);

        if (!checkdate($month, $day, $year)) {
            throw new InvalidArgumentException('Invalid date options.');
        }

        $century = $year >= 2000 ? 3 : 2;
        $yearStr = substr((string) $year, -2);
        $monthStr = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
        $dayStr = str_pad((string) $day, 2, '0', STR_PAD_LEFT);

        $govCodes = array_keys(Governorates::all());
        $govCode = $options['governorate_code'] ?? $govCodes[random_int(0, count($govCodes) - 1)];
        if (!isset(Governorates::all()[$govCode])) {
            throw new InvalidArgumentException('Invalid governorate code.');
        }
        $govStr = str_pad((string) $govCode, 2, '0', STR_PAD_LEFT);

        $serial = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
        $genderDigit = $this->resolveGenderDigit($options['gender'] ?? null);

        $baseId = sprintf('%d%s%s%s%s%s%d', $century, $yearStr, $monthStr, $dayStr, $govStr, $serial, $genderDigit);
        $checkDigit = $this->calculateCheckDigit($baseId);

        return $baseId . $checkDigit;
    }

    private function resolveGenderDigit(?string $gender): int
    {
        if ($gender === null) {
            return random_int(0, 9);
        }

        $normalized = strtolower(trim($gender));
        if ($normalized === 'male') {
            $digits = [1, 3, 5, 7, 9];
            return $digits[random_int(0, 4)];
        }

        if ($normalized === 'female') {
            $digits = [0, 2, 4, 6, 8];
            return $digits[random_int(0, 4)];
        }

        throw new InvalidArgumentException('Gender must be "Male" or "Female".');
    }

    private function calculateCheckDigit(string $base13): int
    {
        if (!preg_match('/^\d{13}$/', $base13)) {
            throw new InvalidArgumentException('Base ID must be exactly 13 digits.');
        }

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += ((int) $base13[$i]) * self::CHECKSUM_MULTIPLIERS[$i];
        }

        $remainder = $sum % 11;
        return abs(11 - $remainder) % 10;
    }
}
