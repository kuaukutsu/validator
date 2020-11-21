<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\CollectionInterface;

interface RuleAggregate extends CollectionInterface
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
