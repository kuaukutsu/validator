<?php

declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\validator\exceptions\ArrayKeyExistsException;
use kuaukutsu\validator\exceptions\InvalidArgumentException;
use kuaukutsu\validator\exceptions\UnknownPropertyException;

final class Validator implements ValidatorInterface
{
    /**
     * @var RuleAggregate|iterable<string, RuleAggregate>
     */
    private iterable $rules;

    /**
     * Validator constructor.
     * @param RuleAggregate|iterable<string, RuleAggregate> $rules
     */
    public function __construct(iterable $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @param string $attributeName
     * @param Rule $rule
     * @throws InvalidArgumentException
     */
    public function addRule(string $attributeName, Rule $rule): void
    {
        if (!is_array($this->rules)) {
            throw new InvalidArgumentException('This type of Validator cannot be extended.');
        }

        if (!isset($this->rules[$attributeName])) {
            $this->rules[$attributeName] = new RuleCollection();
        }

        /** @psalm-suppress MissingThrowsDocblock */
        $this->rules[$attributeName]->attach($rule);
    }

    /**
     * @param string $attributeName
     * @param RuleAggregate $rules
     * @throws InvalidArgumentException
     */
    public function addRules(string $attributeName, RuleAggregate $rules): void
    {
        if (!is_array($this->rules)) {
            throw new InvalidArgumentException('This type of Validator cannot be extended.');
        }

        if (!isset($this->rules[$attributeName])) {
            $this->rules[$attributeName] = new RuleCollection();
        }

        /** @psalm-suppress MissingThrowsDocblock */
        $this->rules[$attributeName]->merge($rules);
    }

    /**
     * @param mixed $value
     * @return ViolationAggregate
     * @throws InvalidArgumentException
     */
    public function validate($value): ViolationAggregate
    {
        if (is_array($this->rules)) {
            if (is_object($value)) {
                return $this->validateObject($value, $this->rules);
            }

            if (is_array($value)) {
                return $this->validateArray($value, $this->rules);
            }
        }

        if (!$this->rules instanceof RuleAggregate) {
            throw new InvalidArgumentException('Rules must be implement RulesBlock');
        }

        return $this->validateValue($value, $this->rules);
    }

    /**
     * @param object $object
     * @param iterable<string, RuleAggregate> $rules
     * @return ViolationAggregate
     */
    private function validateObject(object $object, iterable $rules): ViolationAggregate
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
     * @param iterable<string, RuleAggregate> $rules
     * @return ViolationAggregate
     */
    private function validateArray(array $value, iterable $rules): ViolationAggregate
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
     * @param RuleAggregate $rules
     * @return ViolationAggregate
     */
    private function validateValue($value, RuleAggregate $rules): ViolationAggregate
    {
        $violations = new ViolationCollection();

        /** @var Rule $rule */
        foreach ($rules as $rule) {
            if ($rules->isSkipOnError() && $violations->hasViolations()) {
                return $violations;
            }

            $validate = $rule
                ->skipOnEmpty($rules->isSkipOnEmpty())
                ->validate($value);

            if ($validate->hasViolations()) {
                $violations->merge($validate);
            }
        }

        return $violations;
    }
}
