<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\rules\Length;
use PHPUnit\Framework\TestCase;

class LengthTest extends TestCase
{
    public function testValidate(): void
    {
        $rule = new Length(2,5);

        // positive
        $violations = $rule->validate('qw');
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate('qwe');
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate('');
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('q');
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('qwerty6');
        self::assertTrue($violations->hasViolations());
    }
}
