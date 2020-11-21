<?php

namespace kuaukutsu\validator\tests;

use kuaukutsu\validator\exceptions\ArrayKeyExistsException;
use kuaukutsu\validator\exceptions\InvalidArgumentException;
use kuaukutsu\validator\exceptions\UnknownPropertyException;
use kuaukutsu\validator\rules\Boolean;
use kuaukutsu\validator\rules\GreaterThan;
use kuaukutsu\validator\rules\Type;
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

    public function testSkipOnEmpty(): void
    {
        $rules = RuleCollection::skipOnEmpty();
        $rules->attach(new Type('bool'));
        $rules->attach(new Boolean(true, false));

        $validator = new Validator($rules);

        self::assertTrue($validator->validate('0')->hasViolations());

        self::assertFalse($validator->validate(true)->hasViolations());

        self::assertFalse($validator->validate('')->hasViolations(), 'There must be skip on empty');
    }

    public function testSkipOnEmptyAggregate(): void
    {
        $validator = new Validator(
            RuleCollection::skipOnEmpty(
                new Type(Type::TYPE_INT),
                new GreaterThan(5)
            )
        );

        self::assertFalse($validator->validate('')->hasViolations(), 'There must be skip on empty');

        self::assertCount(1, $validator->validate(4), 'There must be one violation: is GreaterThan');
    }

    public function testSkipOnError(): void
    {
        $rules = RuleCollection::skipOnError();
        $rules->attach(new NotBlank());
        $rules->attach(new Boolean(1, 0));

        $validator = new Validator($rules);

        self::assertCount(1, $validator->validate(''), 'There must be one (first) violation: is Blank');
    }

    public function testSkipOnErrorAggregate(): void
    {
        $validator = new Validator(
            RuleCollection::skipOnError(
                new NotBlank(),
                new Type(Type::TYPE_INT),
                new GreaterThan(5)
            )
        );

        self::assertCount(1, $validator->validate(''), 'There must be one (first) violation: is Blank');

        self::assertCount(1, $validator->validate(4), 'There must be one (first) violation: is GreaterThan');
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

    public function testAddingRulesOneByOne(): void
    {
        $validator = new Validator();
        $validator->addRule('id', new NotBlank());
        $validator->addRule('id', new Type('int'));

        $object = new Entity(1, '');

        // positive
        self::assertFalse($validator->validate($object)->hasViolations());

        $validator->addRule('name', new NotBlank()); // <-- NotBlank
        $validator->addRule('name', new Type('string'));

        // negative
        self::assertTrue($validator->validate($object)->hasViolations());
    }

    public function testAddingRulesInvalidArgumentException(): void
    {
        $validator = new Validator(
            new RuleCollection(
                new NotBlank()
            )
        );

        $this->expectException(InvalidArgumentException::class);

        $validator->addRule('id', new Boolean());
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
