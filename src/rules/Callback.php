<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

final class Callback extends RuleBase
{
    /**
     * @var callable FALSE, if an error occurred during validation of an value
     */
    private $callback;

    private string $message;

    public function __construct(callable $callback, string $message)
    {
        $this->callback = $callback;
        $this->message = $message;
    }

    protected function validateValue($value): void
    {
        if (!($this->callback)($value)) {
            $this->addViolation($this->message);
        }
    }
}
