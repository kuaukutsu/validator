<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\rules\NotBlank;
use PHPUnit\Framework\TestCase;

class NotBlankTest extends TestCase
{
    public function testValidate(): void
    {
        $rule = new NotBlank();

        // positive
        $violations = $rule->validate('notBlank');
        self::assertFalse($violations->hasViolations());

        $violations = $rule->validate(0);
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate('');
        self::assertTrue($violations->hasViolations());
    }

    public function testValidateCallbackEmpty(): void
    {
        $rule = new NotBlank(static fn(string $value): bool => $value === 'empty');

        // positive
        $violations = $rule->validate('notBlank');
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate('empty');
        self::assertTrue($violations->hasViolations());
    }
}
