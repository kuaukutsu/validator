<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use kuaukutsu\validator\RuleBase;

final class Length extends RuleBase
{
    private int $min;

    private int $max;

    private string $charset;

    private string $message = 'This value must be a string.';

    private string $minMessage = 'This value is too short. It should have {{ limit }} character or more.';

    private string $maxMessage = 'This value is too long. It should have {{ limit }} character or less.';

    public function __construct(int $min = 0, int $max = 1, string $charset = 'UTF-8')
    {
        $this->min = $min;
        $this->max = $max;
        $this->charset = $charset;
    }

    /**
     * @param mixed $value
     */
    protected function validateValue($value): void
    {
        if (!is_string($value)) {
            $this->addViolation($this->message);
            return;
        }

        $length = mb_strlen($value, $this->charset);

        if ($length > $this->max) {
            $this->addViolation($this->maxMessage, [
                'limit' => $this->max
            ]);
        }

        if ($length < $this->min) {
            $this->addViolation($this->minMessage, [
                'limit' => $this->min
            ]);
        }
    }
}
