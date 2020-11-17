<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

interface Rule
{
    /**
     * @param mixed $value
     * @return ViolationCollection
     */
    public function validate($value): ViolationCollection;

    /**
     * @param bool $value if validation should be skipped if value validated is empty
     * @return self
     */
    public function skipOnEmpty(bool $value): Rule;
}
