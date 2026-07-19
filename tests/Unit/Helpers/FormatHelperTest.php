<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\FormatHelper;

class FormatHelperTest extends TestCase
{
    /** @test */
    public function it_formats_number_correctly(): void
    {
        $this->assertSame('1.000', FormatHelper::number(1000));
    }

    /** @test */
    public function it_truncates_long_text(): void
    {
        $long = str_repeat('a', 200);
        $this->assertStringEndsWith('...', FormatHelper::truncate($long, 100));
    }
}
