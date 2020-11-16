<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

trait StrictConditions
{
    /**
     * @var bool whether the comparison to value is strict.
     * Defaults to TRUE.
     */
    private bool $strict = true;

    public function strictDisable(): self
    {
        $new = clone $this;
        $new->strict = false;

        return $new;
    }
}
