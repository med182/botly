<?php

namespace App\Tests\Utils;

use App\Utils\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testRandom(): void
    {
        $this->assertSame(6, mb_strlen(Str::random(6)));
        $this->assertSame(10, mb_strlen(Str::random()));
    }
}
