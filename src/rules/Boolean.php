<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

final class Boolean extends RuleBase
{
    use StrictConditions;

    /**
     * @var mixed the value representing true status. Defaults to '1'.
     */
    private $trueValue;

    /**
     * @var mixed the value representing false status. Defaults to '0'.
     */
    private $falseValue;

    private string $message = 'The value must be either "{true}" or "{false}".';

    /**
     * Boolean constructor.
     * @param mixed $trueValue
     * @param mixed $falseValue
     */
    public function __construct($trueValue = '1', $falseValue = '0')
    {
        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;
    }

    /**
     * @param mixed $value
     */
    protected function validateValue($value): void
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        $valid = $this->strict
            ? $value === $this->trueValue || $value === $this->falseValue
            : $value == $this->trueValue || $value == $this->falseValue;

        if (!$valid) {
            $this->addViolation($this->message, [
                'true' => is_bool($this->trueValue) ? 'true' : $this->trueValue,
                'false' => is_bool($this->falseValue) ? 'false' : $this->falseValue,
            ]);
        }
    }
}
