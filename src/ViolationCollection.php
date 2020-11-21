<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\Collection;

/**
 * Class ViolationCollection
 *
 * @psalm-return iterable<Violation>
 * @method getIterator(): \ArrayIterator
 */
final class ViolationCollection extends Collection implements ViolationAggregate
{
    /**
     * @psalm-return class-string
     * @return string
     */
    protected function getType(): string
    {
        return Violation::class;
    }

    public function hasViolations(): bool
    {
        return $this->count() > 0;
    }

    public function getFirstViolation(): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = clone $this->getIterator();
        $iterator->rewind();

        return (string)$iterator->current();
    }

    public function getLastViolation(): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = clone $this->getIterator();
        $iterator->seek(count($iterator) - 1);

        return (string)$iterator->current();
    }
}
