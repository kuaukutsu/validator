<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\rules\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testValidate(): void
    {
        $rule = new Boolean(1,0);

        // positive
        $violations = $rule->validate(1);
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate(0);
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate(true);
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate(-1);
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('false');
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('0');
        self::assertTrue($violations->hasViolations());
    }

    public function testValidateStrictMode(): void
    {
        $rule = (new Boolean(1,0))
            ->strictDisable();

        // positive
        $violations = $rule->validate(1);
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate(0);
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate(true);
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate('string cast to bool');
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate('1');
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate(-1);
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate(2);
        self::assertTrue($violations->hasViolations());
    }
}
