<?php

namespace kuaukutsu\validator\tests\rules;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use kuaukutsu\validator\exceptions\ConstraintDefinitionException;
use kuaukutsu\validator\rules\GreaterThan;
use PHPUnit\Framework\TestCase;

class GreaterThanTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param int|string|DateTimeInterface $value
     * @param array $positive
     * @param array $negative
     * @param bool $orEqual
     */
    public function testValidate($value, array $positive, array $negative, bool $orEqual): void
    {
        $rule = new GreaterThan($value, $orEqual);

        // positive
        foreach ($positive as $comparedValue) {
            $violations = $rule->validate($comparedValue);
            self::assertFalse($violations->hasViolations());
        }

        // negative
        foreach ($negative as $comparedValue) {
            $violations = $rule->validate($comparedValue);
            self::assertTrue($violations->hasViolations());
        }
    }

    /**
     * @dataProvider dataProviderNotStrict
     * @param int|string|DateTimeInterface $value
     * @param array $positive
     * @param array $negative
     * @param bool $orEqual
     */
    public function testValidateNotStrict($value, array $positive, array $negative, bool $orEqual): void
    {
        $rule = (new GreaterThan($value, $orEqual))->strict(false);

        // positive
        foreach ($positive as $comparedValue) {
            $violations = $rule->validate($comparedValue);
            self::assertFalse($violations->hasViolations());
        }

        // negative
        foreach ($negative as $comparedValue) {
            $violations = $rule->validate($comparedValue);
            self::assertTrue($violations->hasViolations());
        }
    }

    public function testConstraintDefinitionException(): void
    {
        $rule = new GreaterThan(new DateTimeImmutable('2 june 2020'));

        $this->expectException(ConstraintDefinitionException::class);

        $rule->validate('2');
    }

    public function dataProvider(): array
    {
        return [
            [
                1,
                [1, 2, 10],
                [0, '1', null],
                true // <-- orEqual
            ],
            [
                1,
                [2, 10],
                [1, '1', null],
                false // <-- not orEqual
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('2 june 2020'), new DateTime('3 june 2020'), '2020-06-02'],
                [new DateTimeImmutable('1 june 2020'), '2019-06-02', null],
                true
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('3 june 2020'), new DateTime('3 june 2020'), '2020-06-03'],
                [new DateTimeImmutable('2 june 2020'), '2020-06-02', null],
                false
            ],
        ];
    }

    public function dataProviderNotStrict(): array
    {
        return [
            [
                1,
                [1, 2, 10, '2'],
                [0, null],
                true
            ],
            [
                1,
                [2, 10, '2'],
                [1, '1', null],
                false
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('2 june 2020'), new DateTime('3 june 2020'), '2020-06-02'],
                [new DateTimeImmutable('1 june 2020'), '2019-06-02', null],
                true
            ],
        ];
    }
}
