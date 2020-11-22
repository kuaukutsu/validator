<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\CollectionInterface;

interface RuleAggregate extends CollectionInterface
{
    public const OPTION_NONE = 0;
    public const OPTION_SKIP_ON_EMPTY = 1; // 1 << 0
    public const OPTION_SKIP_ON_ERROR = 2; // 1 << 1

    /**
     * @param int $options bit flag
     * @param Rule ...$items
     * @return static
     */
    public static function instance(int $options = 0, ...$items): self;

    /**
     * @return bool
     */
    public function isSkipOnError(): bool;

    /**
     * @param bool $value
     * @return static
     */
    public function skipOnError(bool $value): self;

    /**
     * @return bool
     */
    public function isSkipOnEmpty(): bool;

    /**
     * @param bool $value
     * @return static
     */
    public function skipOnEmpty(bool $value): self;
}
