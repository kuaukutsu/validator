<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use kuaukutsu\validator\exceptions\InvalidArgumentException;

interface ValidatorInterface
{
    /**
     * @param string $attributeName
     * @param Rule $rule
     * @throws InvalidArgumentException
     */
    public function addRule(string $attributeName, Rule $rule): void;

    /**
     * @param string $attributeName
     * @param RuleAggregate $rules
     * @throws InvalidArgumentException
     */
    public function addRules(string $attributeName, RuleAggregate $rules): void;

    /**
     * @param mixed $value
     * @return ViolationAggregate
     * @throws InvalidArgumentException
     */
    public function validate($value): ViolationAggregate;
}
