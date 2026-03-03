<?php

declare(strict_types=1);

namespace EgyptianNationalId\Tests;

use EgyptianNationalId\NationalIdService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NationalIdServiceTest extends TestCase
{
    private NationalIdService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NationalIdService();
    }

    public function testGenerateProducesValidId(): void
    {
        $id = $this->service->generate([
            'birth_year' => 1998,
            'birth_month' => 7,
            'birth_day' => 21,
            'governorate_code' => 1,
            'gender' => 'Male',
        ]);

        $this->assertSame(14, strlen($id));
        $this->assertTrue($this->service->validate($id));
        $this->assertSame('Male', $this->service->extractGender($id));
    }

    public function testSanitizeHandlesArabicNumerals(): void
    {
        $sanitized = $this->service->sanitize('٣٠٠٠١٠١٠١٢٣٤٥٦');
        $this->assertSame('30001010123456', $sanitized);
    }

    public function testParseReturnsExpectedShape(): void
    {
        $id = $this->service->generate([
            'birth_year' => 1990,
            'birth_month' => 12,
            'birth_day' => 1,
            'governorate_code' => 21,
            'gender' => 'Female',
        ]);

        $data = $this->service->parse($id);

        $this->assertSame($id, $data['national_id']);
        $this->assertSame(1990, $data['birth_year']);
        $this->assertSame(12, $data['birth_month']);
        $this->assertSame(1, $data['birth_day']);
        $this->assertSame('Female', $data['gender']);
        $this->assertSame(21, $data['governorate']['code']);
    }

    public function testParseThrowsForInvalidId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->parse('123');
    }
}
