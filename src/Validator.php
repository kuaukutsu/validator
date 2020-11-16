<?php

declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\validator\exceptions\ArrayKeyExistsException;
use kuaukutsu\validator\exceptions\InvalidArgumentException;
use kuaukutsu\validator\exceptions\UnknownPropertyException;

final class Validator
{
    /**
     * @var RuleCollection|iterable<string, RuleCollection>
     */
    private iterable $rules;

    /**
     * Validator constructor.
     * @param RuleCollection|iterable<string, RuleCollection> $rules
     */
    public function __construct(iterable $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param mixed $value
     * @return ViolationCollection
     * @throws InvalidArgumentException
     */
    public function validate($value): ViolationCollection
    {
        if (is_array($this->rules)) {
            if (is_object($value)) {
                return $this->validateObject($value, $this->rules);
            }

            if (is_array($value)) {
                return $this->validateArray($value, $this->rules);
            }
        }

        if (!$this->rules instanceof RuleCollection) {
            throw new InvalidArgumentException('Rules must be implement RuleCollection');
        }

        return $this->validateValue($value, $this->rules);
    }

    /**
     * @param object $object
     * @param iterable<string, RuleCollection> $rules
     * @return ViolationCollection
     */
    private function validateObject(object $object, iterable $rules): ViolationCollection
    {
        $violations = new ViolationCollection();

        foreach ($rules as $key => $rule) {
            if (!property_exists($object, $key)) {
                throw new UnknownPropertyException("Property '$key' not found in class " . get_class($object));
            }

            $collection = $this->validateValue($object->{$key}, $rule);
            if ($collection->hasViolations()) {
                /** @var Violation $violation */
                foreach ($collection as $violation) {
                    $violations->attach($violation->setAttributeName($key));
                }
            }
        }

        return $violations;
    }

    /**
     * @param array $value
     * @param iterable<string, RuleCollection> $rules
     * @return ViolationCollection
     */
    private function validateArray(array $value, iterable $rules): ViolationCollection
    {
        $violations = new ViolationCollection();

        foreach ($rules as $key => $rule) {
            if (!array_key_exists($key, $value)) {
                throw new ArrayKeyExistsException("Key '$key' not exists.");
            }

            $collection = $this->validateValue($value[$key], $rule);
            if ($collection->hasViolations()) {
                /** @var Violation $violation */
                foreach ($collection as $violation) {
                    $violations->attach($violation->setAttributeName($key));
                }
            }
        }

        return $violations;
    }

    /**
     * @param mixed $value
     * @param RuleCollection $rules
     * @return ViolationCollection
     */
    private function validateValue($value, RuleCollection $rules): ViolationCollection
    {
        $violations = new ViolationCollection();

        /** @var Rule $rule */
        foreach ($rules as $rule) {
            if ($rules->isSkipOnError() && $violations->hasViolations()) {
                break;
            }

            $validate = $rule->validate($value);
            if ($validate->hasViolations()) {
                $violations->merge($validate);
            }
        }

        return $violations;
    }
}
