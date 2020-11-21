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
     * @param Rule ...$items
     * @return static
     */
    public static function skipOnError(...$items): self;

    /**
     * @return bool
     */
    public function isSkipOnEmpty(): bool;

    /**
     * @param Rule ...$items
     * @return static
     */
    public static function skipOnEmpty(...$items): self;
}
