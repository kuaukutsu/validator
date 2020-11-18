<?php
declare(strict_types=1);

namespace kuaukutsu\validator\tests\data;

use kuaukutsu\ds\collection\Collection;
use kuaukutsu\validator\Rule;
use kuaukutsu\validator\RuleAggregate;

final class SkipOnErrorAggregate extends Collection implements RuleAggregate
{
    /**
     * @psalm-return class-string
     * @return string
     */
    protected function getType(): string
    {
        return Rule::class;
    }

    public function isSkipOnError(): bool
    {
        return true;
    }

    public function skipOnError(bool $value): RuleAggregate
    {
        return $this;
    }
}
