<?php

namespace kuaukutsu\validator\tests\rules;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use kuaukutsu\validator\rules\EqualTo;
use PHPUnit\Framework\TestCase;

class EqualToTest extends TestCase
{
    /**
     * @dataProvider dataProviderEqual
     * @param int|string|DateTimeInterface $value
     * @param array $positive
     * @param array $negative
     */
    public function testEqual($value, array $positive, array $negative): void
    {
        $rule = new EqualTo($value, false);

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
     * @dataProvider dataProviderIdentical
     * @param int|string|DateTimeInterface $value
     * @param array $positive
     * @param array $negative
     */
    public function testIdentical($value, array $positive, array $negative): void
    {
        $rule = new EqualTo($value);

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

    public function dataProviderEqual(): array
    {
        return [
            [
                10,
                [10,'10'],
                [1, '1', null]
            ],
            [
                'abracadabra',
                ['abracadabra', new class { public function __toString() { return 'abracadabra'; }}],
                [1, 'ebracadabra', null]
            ],
            [
                new DateTimeImmutable('1 june 2019'),
                ['2019-06-01', new DateTime('1 june 2019')],
                ['2019-06-02', new DateTime('2 june 2019'), null]
            ]
        ];
    }

    public function dataProviderIdentical(): array
    {
        return [
            [
                10,
                [10],
                [1, '10', null]
            ],
            [
                'abracadabra',
                ['abracadabra'],
                [1, 'ebracadabra', new class { public function __toString() { return 'abracadabra'; }}, null]
            ],
            [
                new DateTimeImmutable('1 june 2019'),
                ['2019-06-01', new DateTimeImmutable('1 june 2019'), new DateTime('1 june 2019')],
                [new DateTime('2 june 2019'), null]
            ]
        ];
    }
}
