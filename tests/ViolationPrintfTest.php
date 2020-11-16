<?php

namespace kuaukutsu\validator\tests;

use JsonException;
use kuaukutsu\validator\RuleCollection;
use kuaukutsu\validator\rules\Boolean;
use kuaukutsu\validator\rules\NotBlank;
use kuaukutsu\validator\tests\data\Entity;
use kuaukutsu\validator\Validator;
use kuaukutsu\validator\ViolationPrintf;
use PHPUnit\Framework\TestCase;

class ViolationPrintfTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testSimple(): void
    {
        $validator = new Validator(
            new RuleCollection(
                new NotBlank(),
                new Boolean(1, 0)
            )
        );

        // note: two violations: is Blank & not Boolean
        $violations = $validator->validate('');

        $printf = new ViolationPrintf($violations);

        self::assertEquals(
            [
                'This value should not be blank.',
                'The value must be either "1" or "0".'
            ],
            $printf->toArray()
        );

        self::assertEquals(
            '["This value should not be blank.","The value must be either \"1\" or \"0\"."]',
            $printf->toJson()
        );
    }

    public function testObject(): void
    {
        $validator = new Validator([
            'id' => new RuleCollection(
                new NotBlank(),
                new Boolean(true, false)
            ),
            'name' => new RuleCollection(
                new NotBlank(),
                new Boolean(true, false)
            ),
        ]);

        $object = new Entity(1, '');

        // note:
        // id: one violation: not Boolean
        // name: two violations: is Blank & not Boolean
        $printf = new ViolationPrintf($validator->validate($object));

        self::assertEquals(
            [
                'id' => [
                    'The value must be either "true" or "false".'
                ],
                'name' => [
                    'This value should not be blank.',
                    'The value must be either "true" or "false".'
                ]
            ],
            $printf->toArray()
        );

        self::assertEquals(
            'The value must be either "true" or "false".',
            $printf->getFirstViolation()
        );
    }

    public function testObjectFormatByAttributeName(): void
    {
        $validator = new Validator([
            'id' => new RuleCollection(
                new NotBlank(),
                new Boolean(true, false)
            ),
            'name' => new RuleCollection(
                new NotBlank(),
                new Boolean(true, false)
            ),
        ]);

        $object = new Entity(1, '');

        // note:
        // id: one violation: not Boolean
        // name: two violations: is Blank & not Boolean
        $printf = new ViolationPrintf($validator->validate($object));

        self::assertEquals(
            [
                'This value should not be blank.',
                'The value must be either "true" or "false".'
            ],
            $printf->formatByAttributeName('name')->toArray()
        );

        self::assertEquals(
            'This value should not be blank.',
            $printf->formatByAttributeName('name')->getFirstViolation()
        );

        self::assertEmpty($printf->formatByAttributeName('notexists')->toArray());
    }
}
