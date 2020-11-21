<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\Collection;

/**
 * Class RuleCollection
 *
 * @psalm-return iterable<Rule>
 * @method getIterator(): \ArrayIterator
 */
final class RuleCollection extends Collection implements RuleAggregate
{
    /**
     * @var bool if validation should be skipped if value validated is empty
     */
    private bool $skipOnEmpty = false;

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

    public function isSkipOnEmpty(): bool
    {
        return $this->skipOnEmpty;
    }

    /**
     * @param Rule ...$items
     * @return static
     */
    public static function skipOnEmpty(...$items): self
    {
        $collection = new self(...$items);
        $collection->skipOnEmpty = true;

        return $collection;
    }

    public function isSkipOnError(): bool
    {
        return $this->skipOnError;
    }

    /**
     * @param Rule ...$items
     * @return static
     */
    public static function skipOnError(...$items): self
    {
        $collection = new self(...$items);
        $collection->skipOnError = true;

        return $collection;
    }
}
