<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use kuaukutsu\validator\RuleBase;

final class NotBlank extends RuleBase
{
    /**
     * @var callable return TRUE if Empty, else FALSE
     */
    private $assertEmpty;

    private string $message = 'This value should not be blank.';

    /**
     * NotBlank constructor.
     * @param callable|null $assertEmpty return TRUE if Empty, else FALSE
     */
    public function __construct(callable $assertEmpty = null)
    {
        /** @psalm-suppress MissingClosureParamType */
        $this->assertEmpty = $assertEmpty ?? fn($value): bool => $this->isEmpty($value);
    }

    /**
     * @param mixed $value
     */
    protected function validateValue($value): void
    {
        if (($this->assertEmpty)($value)) {
            $this->addViolation($this->message);
        }
    }
}
