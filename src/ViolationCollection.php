<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\Collection;

final class ViolationCollection extends Collection
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
}
