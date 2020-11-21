<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\validator\exceptions\InvalidArgumentException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @return ViolationAggregate
     * @throws InvalidArgumentException
     */
    public function validate($value): ViolationAggregate;
}
