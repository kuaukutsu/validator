<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

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
        $this->assertEmpty = $assertEmpty ?? fn($value): bool => $this->checkOnEmpty($value);
    }

    public function message(string $message): self
    {
        $self = clone $this;
        $self->message = $message;

        return $self;
    }

    /**
     * Отключаем игнорирование проверки на пустое значение (skipOnEmpty).
     *
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value): bool
    {
        return false;
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

    /**
     * @param mixed $value
     * @return bool
     */
    private function checkOnEmpty($value): bool
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        return $value === null || $value === '' || $value === [];
    }
}
