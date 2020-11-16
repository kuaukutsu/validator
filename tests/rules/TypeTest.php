<?php

namespace kuaukutsu\validator\tests\rules;

use kuaukutsu\validator\rules\Type;
use kuaukutsu\validator\tests\data\Entity;
use PHPUnit\Framework\TestCase;
use stdClass;

class TypeTest extends TestCase
{
    /**
     * @dataProvider dataProviderTypes()
     * @param string $type
     * @param array $positive
     * @param array $negative
     */
    public function testSimple(string $type, array $positive, array $negative): void
    {
        $rule = new Type($type);

        // positive
        foreach ($positive as $value) {
            $violations = $rule->validate($value);
            self::assertFalse($violations->hasViolations());
        }

        // negative
        foreach ($negative as $value) {
            $violations = $rule->validate($value);
            self::assertTrue($violations->hasViolations());
        }
    }

    public function dataProviderTypes(): array
    {
        return [
            [
                Type::TYPE_NULL,
                [null],
                [1, '1', false]
            ],
            [
                Type::TYPE_BOOL,
                [true, false],
                [1, 0, '1', 'false', null]
            ],
            [
                Type::TYPE_STRING,
                ['str', ''],
                [1, false, [], null]
            ],
            [
                Type::TYPE_INT,
                [1, 0, -11],
                ['1', true, [], null]
            ],
            [
                Type::TYPE_NUMBER,
                [1, 0, -11, '11', '-2'],
                [true, [], null]
            ],
            [
                Type::TYPE_SCALAR,
                [1, 0, -11, '11', '-2', 'string', true, 1.2],
                [[], null]
            ],
            [
                Entity::class,
                [new Entity(1,'test')],
                [new stdClass(), null]
            ],
        ];
    }
}
