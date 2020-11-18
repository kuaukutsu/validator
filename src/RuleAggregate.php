<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use Ds\Collection;

interface RuleAggregate extends Collection
{
    /**
     * @return bool
     */
    public function isSkipOnError(): bool;

    /**
     * @param bool $value
     * @return static
     */
    public function skipOnError(bool $value): self;
}
