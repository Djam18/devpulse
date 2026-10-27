<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\HexColorRule;
use Tests\TestCase;

final class HexColorRuleTest extends TestCase
{
    public function test_validates_correct_hex_colors(): void
    {
        $rule = new HexColorRule;

        $failed = false;
        $failCallback = function () use (&$failed) {
            $failed = true;
        };

        $rule->validate('color', '#7D2D68', $failCallback);
        $this->assertFalse($failed);

        $rule->validate('color', '#FFF', $failCallback);
        $this->assertFalse($failed);
    }

    public function test_rejects_invalid_hex_colors(): void
    {
        $rule = new HexColorRule;

        $failed = false;
        $failCallback = function () use (&$failed) {
            $failed = true;
        };

        $rule->validate('color', 'red', $failCallback);
        $this->assertTrue($failed);

        $failed = false;
        $rule->validate('color', '#12345', $failCallback);
        $this->assertTrue($failed);
    }
}
