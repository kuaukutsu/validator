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

    public static function instance(int $options = 0, ...$items): self
    {
        $collection = new self(...$items);

        if ($options & self::OPTION_SKIP_ON_EMPTY) {
            $collection->skipOnEmpty = true;
        }

        if ($options & self::OPTION_SKIP_ON_ERROR) {
            $collection->skipOnError = true;
        }

        return $collection;
    }


    public function isSkipOnEmpty(): bool
    {
        return $this->skipOnEmpty;
    }

    /**
     * @param bool $value
     * @return static
     */
    public function skipOnEmpty(bool $value): self
    {
        $collection = clone $this;
        $collection->skipOnEmpty = $value;

        return $collection;
    }

    public function isSkipOnError(): bool
    {
        return $this->skipOnError;
    }

    /**
     * @param bool $value
     * @return static
     */
    public function skipOnError(bool $value): self
    {
        $collection = clone $this;
        $collection->skipOnError = $value;

        return $collection;
    }
}
