<?php

namespace kuaukutsu\validator\tests\rules;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use kuaukutsu\validator\exceptions\ConstraintDefinitionException;
use kuaukutsu\validator\rules\LessThan;
use PHPUnit\Framework\TestCase;

class LessThanTest extends TestCase
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
        $rule = new LessThan($value, $orEqual);

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
        $rule = (new LessThan($value, $orEqual))->strict(false);

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
        $rule = new LessThan(new DateTimeImmutable('2 june 2020'));

        $this->expectException(ConstraintDefinitionException::class);

        $rule->validate('2');
    }

    public function dataProvider(): array
    {
        return [
            [
                10,
                [1, 2, 10],
                [100, '1', null],
                true // <-- orEqual
            ],
            [
                10,
                [2, 9],
                [10, 11, '10', null],
                false // <-- not orEqual
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('2 june 2020'), new DateTime('1 june 2020'), '2020-06-01'],
                [new DateTimeImmutable('3 june 2020'), '2020-07-02', null],
                true
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('1 june 2020'), new DateTime('1 june 2020'), '2020-06-01'],
                [new DateTimeImmutable('2 june 2020'), '2020-06-02', null],
                false
            ],
        ];
    }

    public function dataProviderNotStrict(): array
    {
        return [
            [
                10,
                [1, 2, 10, '2', null],
                [11, '12'],
                true
            ],
            [
                10,
                [2, 9, '7', null],
                [10, '11'],
                false
            ],
            [
                new DateTimeImmutable('2 june 2020'),
                [new DateTimeImmutable('2 june 2020'), new DateTime('1 june 2020'), '2020-06-01', null],
                [new DateTimeImmutable('3 june 2020'), '2020-07-02'],
                true
            ],
        ];
    }
}
