<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\ds\collection\CollectionInterface;

interface ViolationAggregate extends CollectionInterface
{
    public function hasViolations(): bool;

    public function getFirstViolation(): string;

    public function getLastViolation(): string;
}
