<?php
declare(strict_types=1);

namespace kuaukutsu\validator\tests\data;

use kuaukutsu\validator\Rule;
use kuaukutsu\validator\rules\GreaterThan;
use kuaukutsu\validator\rules\SkipConditions;
use kuaukutsu\validator\rules\Type;
use kuaukutsu\validator\ViolationCollection;

/**
 * Example Custom Group Rule
 */
final class IntegerUnsignedRule implements Rule
{
    use SkipConditions;

    public function validate($value): ViolationCollection
    {
        $violations = (new Type(Type::TYPE_INT))->validate($value);
        if ($violations->hasViolations()) {
            return $violations;
        }

        // second operation validate
        return (new GreaterThan(0))->validate($value);
    }
}
