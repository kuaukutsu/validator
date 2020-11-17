<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use DateTimeInterface;

final class GreaterThan extends CompareBase
{
    private bool $orEqual;

    private string $message = 'This value should be greater than {value}.';

    private string $messageOrEqual = 'This value should be greater than or equal to {value}.';

    /**
     * GreaterThan constructor.
     * @param float|int|string|DateTimeInterface $baseValue
     * @param bool $orEqual
     */
    public function __construct($baseValue, bool $orEqual = true)
    {
        $this->orEqual = $orEqual;

        parent::__construct($baseValue);
    }

    /**
     * @param float|int|string|DateTimeInterface $baseValue
     * @param float|int|string|DateTimeInterface $comparedValue
     */
    protected function compareTo($baseValue, $comparedValue): void
    {
        if ($this->orEqual) {
            if (!($comparedValue >= $baseValue)) {
                $this->addViolation($this->messageOrEqual, [
                    'value' => ($comparedValue instanceof DateTimeInterface)
                        ? $comparedValue->format('c')
                        : $comparedValue
                ]);
            }

            return;
        }

        if (!($comparedValue > $baseValue)) {
            $this->addViolation($this->message, [
                'value' => ($comparedValue instanceof DateTimeInterface)
                    ? $comparedValue->format('c')
                    : $comparedValue
            ]);
        }
    }
}
