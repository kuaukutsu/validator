<?php

namespace kuaukutsu\validator\tests\rules;

use Exception;
use kuaukutsu\validator\rules\Callback;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testValidate(): void
    {
        $rule = new Callback(
            fn(string $value): bool => $value !== 'test',
            'Description violation'
        );

        // positive
        $violations = $rule->validate('test');
        self::assertFalse($violations->hasViolations());

        // negative
        $violations = $rule->validate('');
        self::assertTrue($violations->hasViolations());

        $violations = $rule->validate('qwerty6');
        self::assertTrue($violations->hasViolations());
        self::assertEquals('Description violation', $violations->getIterator()->current());
    }
}
