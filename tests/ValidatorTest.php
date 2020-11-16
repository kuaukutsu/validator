<?php

namespace kuaukutsu\validator\tests;

use kuaukutsu\validator\exceptions\ArrayKeyExistsException;
use kuaukutsu\validator\exceptions\InvalidArgumentException;
use kuaukutsu\validator\exceptions\UnknownPropertyException;
use kuaukutsu\validator\rules\Boolean;
use kuaukutsu\validator\tests\data\Entity;
use kuaukutsu\validator\RuleCollection;
use kuaukutsu\validator\rules\NotBlank;
use kuaukutsu\validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testSimpleValue(): void
    {
        $validator = new Validator(
            new RuleCollection(
                new NotBlank(),
                new Boolean(1, 0)
            )
        );

        // positive
        self::assertFalse($validator->validate(1)->hasViolations());

        // negative value
        self::assertCount(1, $validator->validate(2), 'There must be one violation: not Boolean');

        // negative empty value
        self::assertCount(2, $validator->validate(''), 'There must be two violations: is Blank & not Boolean');
    }

    public function testSkipOnError(): void
    {
        $rules = new RuleCollection(
            new NotBlank(),
            new Boolean(1, 0)
        );

        $validator = new Validator($rules);
        self::assertCount(2, $validator->validate(''), 'There must be two violations: is Blank & not Boolean');

        $validator = new Validator($rules->skipOnError(true));
        self::assertCount(1, $validator->validate(''), 'There must be one (first) violation: is Blank');
    }

    public function testValueProperties(): void
    {
        $validator = new Validator([
            'id' => new RuleCollection(
                new NotBlank()
            ),
            'name' => new RuleCollection(
                new NotBlank()
            ),
        ]);

        $object = new Entity(1, '');

        self::assertTrue($validator->validate($object)->hasViolations());
    }

    public function testArraySimple(): void
    {
        $validator = new Validator(
            new RuleCollection(
                new NotBlank()
            )
        );

        // positive
        self::assertFalse($validator->validate(['one' => 1, 'two' => 2])->hasViolations());

        // negative
        self::assertTrue($validator->validate([])->hasViolations());
    }

    public function testArrayValue(): void
    {
        $validator = new Validator([
            'one' => new RuleCollection(
                new NotBlank()
            ),
            'two' => new RuleCollection(
                new NotBlank()
            ),
        ]);

        $array = [
            'one' => 1,
            'two' => 2,
        ];

        self::assertFalse($validator->validate($array)->hasViolations());

        $array = [
            'one' => 1,
            'two' => '', // <-- fail
        ];

        self::assertTrue($validator->validate($array)->hasViolations());

        $array = [
            'one' => 1,
        ];

        $this->expectException(ArrayKeyExistsException::class);

        self::assertTrue($validator->validate($array)->hasViolations());
    }

    public function testPropertyNotExist(): void
    {
        $validator = new Validator([
            'id' => new RuleCollection(
                new NotBlank()
            ),
            'name' => new RuleCollection(
                new NotBlank()
            ),
            'except' => new RuleCollection(
                new NotBlank()
            )
        ]);

        $object = new Entity(1, 'test');

        $this->expectException(UnknownPropertyException::class);

        $validator->validate($object);
    }

    public function testInvalidArgument(): void
    {
        /** @psalm-suppress InvalidArgument */
        $validator = new Validator([
            'test' => 'bad argument'
        ]);

        $this->expectException(InvalidArgumentException::class);

        $validator->validate('value');
    }
}
