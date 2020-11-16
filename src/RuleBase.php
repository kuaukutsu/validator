<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\validator\exceptions\MethodCalledIncorrectlyException;

abstract class RuleBase implements Rule
{
    private bool $skipOnEmpty = false;

    private ?ViolationCollection $violations = null;

    /**
     * Get name of the rule to be used when rule is converted to array.
     * By default it returns base name of the class, first letter in lowercase.
     *
     * @return string
     */
    public function getName(): string
    {
        $className = static::class;
        /** @psalm-suppress PossiblyFalseOperand */
        return lcfirst(substr($className, strrpos($className, '\\') + 1));
    }

    /**
     * @param bool $value if validation should be skipped if value validated is empty
     * @return self
     */
    public function skipOnEmpty(bool $value): self
    {
        $new = clone $this;
        $new->skipOnEmpty = $value;

        return $new;
    }

    /**
     * @param mixed $value
     */
    abstract protected function validateValue($value): void;

    /**
     * @param mixed $value
     * @return ViolationCollection
     */
    public function validate($value): ViolationCollection
    {
        $this->violations = new ViolationCollection();

        if ($this->skipOnEmpty && $this->isEmpty($value)) {
            return $this->violations;
        }

        $this->validateValue($value);

        return $this->violations;
    }

    /**
     * @param string $message
     * @param array<string, mixed> $parameters
     * @psalm-suppress MissingThrowsDocblock
     */
    protected function addViolation(string $message, array $parameters = []): void
    {
        if ($this->violations === null) {
            throw new MethodCalledIncorrectlyException('Must be called from validateValue.');
        }

        /** @psalm-suppress MissingThrowsDocblock */
        $this->violations->attach(new Violation($message, $parameters));
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value): bool
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        return $value === null || $value === '' || $value === [];
    }
}
