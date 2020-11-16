<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\Collection;

final class RuleCollection extends Collection
{
    /**
     * @var bool by default, if an error occurred during validation of an attribute,
     * further rules for this attribute are skipped.
     */
    private bool $skipOnError = false;

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
        return $this->skipOnError;
    }

    public function skipOnError(bool $skipOnError): self
    {
        $collection = clone $this;
        $collection->skipOnError = $skipOnError;

        return $collection;
    }
}
