<?php

declare(strict_types=1);

namespace EgyptianNationalId\Support;

final class Governorates
{
    /**
     * @return array<int, array{code:int,name_en:string,name_ar:string,region:string}>
     */
    public static function all(): array
    {
        return [
            1 => ['code' => 1, 'name_en' => 'Cairo', 'name_ar' => 'Al Qahirah', 'region' => 'Cairo'],
            21 => ['code' => 21, 'name_en' => 'Giza', 'name_ar' => 'Al Jizah', 'region' => 'Cairo'],
            14 => ['code' => 14, 'name_en' => 'Qalyubia', 'name_ar' => 'Al Qalyubiyah', 'region' => 'Cairo'],

            2 => ['code' => 2, 'name_en' => 'Alexandria', 'name_ar' => 'Al Iskandariyah', 'region' => 'Alexandria'],
            18 => ['code' => 18, 'name_en' => 'Beheira', 'name_ar' => 'Al Buhayrah', 'region' => 'Alexandria'],
            33 => ['code' => 33, 'name_en' => 'Matrouh', 'name_ar' => 'Matruh', 'region' => 'Alexandria'],

            11 => ['code' => 11, 'name_en' => 'Damietta', 'name_ar' => 'Dumyat', 'region' => 'Delta'],
            12 => ['code' => 12, 'name_en' => 'Dakahlia', 'name_ar' => 'Ad Daqahliyah', 'region' => 'Delta'],
            13 => ['code' => 13, 'name_en' => 'Sharqia', 'name_ar' => 'Ash Sharqiyah', 'region' => 'Delta'],
            15 => ['code' => 15, 'name_en' => 'Kafr El Sheikh', 'name_ar' => 'Kafr ash Shaykh', 'region' => 'Delta'],
            16 => ['code' => 16, 'name_en' => 'Gharbia', 'name_ar' => 'Al Gharbiyah', 'region' => 'Delta'],
            17 => ['code' => 17, 'name_en' => 'Monufia', 'name_ar' => 'Al Minufiyah', 'region' => 'Delta'],

            3 => ['code' => 3, 'name_en' => 'Port Said', 'name_ar' => 'Bur Sa id', 'region' => 'Canal'],
            4 => ['code' => 4, 'name_en' => 'Suez', 'name_ar' => 'As Suways', 'region' => 'Canal'],
            19 => ['code' => 19, 'name_en' => 'Ismailia', 'name_ar' => 'Al Isma iliyah', 'region' => 'Canal'],
            34 => ['code' => 34, 'name_en' => 'North Sinai', 'name_ar' => 'Shamal Sina', 'region' => 'Canal'],
            35 => ['code' => 35, 'name_en' => 'South Sinai', 'name_ar' => 'Janub Sina', 'region' => 'Canal'],

            22 => ['code' => 22, 'name_en' => 'Beni Suef', 'name_ar' => 'Bani Suwayf', 'region' => 'UpperEgyptNorth'],
            23 => ['code' => 23, 'name_en' => 'Fayoum', 'name_ar' => 'Al Fayyum', 'region' => 'UpperEgyptNorth'],
            24 => ['code' => 24, 'name_en' => 'Minya', 'name_ar' => 'Al Minya', 'region' => 'UpperEgyptNorth'],

            25 => ['code' => 25, 'name_en' => 'Asyut', 'name_ar' => 'Asyut', 'region' => 'UpperEgyptMiddle'],
            32 => ['code' => 32, 'name_en' => 'New Valley', 'name_ar' => 'Al Wadi al Jadid', 'region' => 'UpperEgyptMiddle'],

            26 => ['code' => 26, 'name_en' => 'Sohag', 'name_ar' => 'Suhaj', 'region' => 'UpperEgyptSouth'],
            27 => ['code' => 27, 'name_en' => 'Qena', 'name_ar' => 'Qina', 'region' => 'UpperEgyptSouth'],
            28 => ['code' => 28, 'name_en' => 'Aswan', 'name_ar' => 'Aswan', 'region' => 'UpperEgyptSouth'],
            29 => ['code' => 29, 'name_en' => 'Luxor', 'name_ar' => 'Al Uqsur', 'region' => 'UpperEgyptSouth'],

            88 => ['code' => 88, 'name_en' => 'Foreign', 'name_ar' => 'Kharij Misr', 'region' => 'Foreign'],
        ];
    }
}
