<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use DateTimeInterface;

final class EqualTo extends CompareBase
{
    private string $message = 'This value should be equal to {value}.';

    private string $messageIdetical = 'This value should be identical to {value).';

    /**
     * @param float|int|string|DateTimeInterface $baseValue
     * @param float|int|string|DateTimeInterface $comparedValue
     */
    protected function compareTo($baseValue, $comparedValue): void
    {
        if ($this->isStrict()) {
            if ($baseValue instanceof DateTimeInterface && $comparedValue instanceof DateTimeInterface) {
                if (!($baseValue->format('U') === $comparedValue->format('U'))) {
                    $this->addViolation($this->messageIdetical, [
                        'value' => $comparedValue->format('c')
                    ]);
                }

                return;
            }

            if ($baseValue !== $comparedValue) {
                $this->addViolation($this->messageIdetical, [
                    'value' => $comparedValue
                ]);
            }

            return;
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        if ($baseValue != $comparedValue) {
            $this->addViolation($this->message, [
                'value' => ($comparedValue instanceof DateTimeInterface)
                    ? $comparedValue->format('c')
                    : $comparedValue
            ]);
        }
    }
}
