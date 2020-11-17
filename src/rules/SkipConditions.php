<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use kuaukutsu\validator\Rule;

trait SkipConditions
{
    /**
     * @var bool if validation should be skipped if value validated is empty
     */
    private bool $skipOnEmpty = false;

    /**
     * @param bool $value if validation should be skipped if value validated is empty
     * @return self|Rule
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function skipOnEmpty(bool $value): Rule
    {
        $new = clone $this;
        $new->skipOnEmpty = $value;

        return $new;
    }
}
