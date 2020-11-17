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

    public function isStrict(): bool
    {
        return $this->strict;
    }

    public function strict(bool $value): self
    {
        $new = clone $this;
        $new->strict = $value;

        return $new;
    }
}
