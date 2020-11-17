<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use kuaukutsu\validator\exceptions\ConstraintDefinitionException;

abstract class CompareBase extends RuleBase
{
    use StrictConditions;

    /**
     * @var float|int|string|DateTimeInterface
     */
    private $baseValue;

    private string $messageType = 'This value should be of type {type}.';

    /**
     * CompareBase constructor.
     * @param string|float|int|DateTimeInterface $baseValue
     * @param bool $strict
     */
    public function __construct($baseValue, bool $strict = true)
    {
        $this->strict = $strict;
        $this->baseValue = $baseValue;
    }

    /**
     * @param float|int|string|DateTimeInterface $baseValue
     * @param float|int|string|DateTimeInterface $comparedValue
     */
    abstract protected function compareTo($baseValue, $comparedValue): void;

    /**
     * @param mixed $value
     * @throws ConstraintDefinitionException
     */
    protected function validateValue($value): void
    {
        if (is_string($value) && $this->baseValue instanceof DateTimeInterface) {
            // If $value is immutable, convert the compared value to a DateTimeImmutable too, otherwise use DateTime
            $dateTimeClass = $this->baseValue instanceof DateTimeImmutable ? DateTimeImmutable::class : DateTime::class;

            try {
                $value = new $dateTimeClass($value);
            } catch (Exception $e) {
                throw new ConstraintDefinitionException(
                    sprintf('The compared value could not be converted to a "%s".', $dateTimeClass)
                );
            }
        }

        // strict type
        if ($this->isStrict() && gettype($this->baseValue) !== gettype($value)) {
            $this->addViolation($this->messageType, [
                'type' => gettype($this->baseValue)
            ]);

            return;
        }

        /** @psalm-suppress MixedArgument */
        $this->compareTo($this->baseValue, $value);
    }
}
