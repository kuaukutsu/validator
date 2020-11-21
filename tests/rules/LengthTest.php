<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\exceptions\InvalidArgumentException;
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

    public function testMessage(): void
    {
        $rule = (new Length(2, 5))
            ->minMessage('TestMinMessage')
            ->maxMessage('TestMaxMessage');

        $violations = $rule->validate('q');
        self::assertTrue($violations->hasViolations());
        self::assertEquals('TestMinMessage', $violations->getFirstViolation());

        $violations = $rule->validate('qwerty6');
        self::assertTrue($violations->hasViolations());
        self::assertEquals('TestMaxMessage', $violations->getFirstViolation());
    }

    public function testTypeException(): void
    {
        $rule = new Length(2,5);

        $this->expectException(InvalidArgumentException::class);

        $rule->validate(12345);
    }
}
