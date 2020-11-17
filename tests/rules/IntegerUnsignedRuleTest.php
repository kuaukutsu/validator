<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\tests\data\IntegerUnsignedRule;
use PHPUnit\Framework\TestCase;

class IntegerUnsignedRuleTest extends TestCase
{
    public function testValidate(): void
    {
        $rule = new IntegerUnsignedRule();

        // positive
        $violations = $rule->validate(1);
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate(0);
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate(-10);
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('');
        self::assertTrue($violations->hasViolations());
    }

    public function testSkipOnEmpty(): void
    {
        $rule = (new IntegerUnsignedRule())->skipOnEmpty(true);

        // positive
        $violations = $rule->validate('');
        self::assertTrue($violations->hasViolations());

        // negative
        $violations = $rule->validate(-1);
        self::assertTrue($violations->hasViolations());
    }
}
