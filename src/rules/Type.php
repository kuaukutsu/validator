<?php
declare(strict_types=1);

namespace kuaukutsu\validator\rules;

final class Type extends RuleBase
{
    public const TYPE_NULL = 'null';
    public const TYPE_BOOL = 'boolean';
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'integer';
    public const TYPE_NUMBER = 'numeric';
    public const TYPE_FLOAT = 'float';
    public const TYPE_ARRAY = 'array';
    public const TYPE_SCALAR = 'scalar';
    public const TYPE_OBJECT = 'object';
    public const TYPE_CALLABLE = 'callable';
    public const TYPE_COUNTABLE = 'countable';
    public const TYPE_ITERABLE = 'iterable';
    public const TYPE_RESOURCE = 'resource';

    private string $type;

    private string $message = 'This value should be of type {type}.';

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    protected function validateValue($value): void
    {
        if (($funcType = $this->getFunctionByType($this->type)) && $funcType($value)) {
            return;
        }

        if ($value instanceof $this->type) {
            return;
        }

        $this->addViolation($this->message, ['type' => $this->type]);
    }

    private function getFunctionByType(string $type): ?string
    {
        $funcMap = [
            'null' => 'is_null',
            'bool' => 'is_bool',
            'boolean' => 'is_bool',
            'str' => 'is_string',
            'string' => 'is_string',
            'int' => 'is_int',
            'integer' => 'is_int',
            'number' => 'is_numeric',
            'numeric' => 'is_numeric',
            'float' => 'is_float',
            'double' => 'is_float',
            'real' => 'is_float',
            'array' => 'is_array',
            'scalar' => 'is_scalar',
            'object' => 'is_object',
            'callable' => 'is_callable',
            'countable' => 'is_countable',
            'iterable' => 'is_iterable',
            'resource' => 'is_resource',
        ];

        return $funcMap[strtolower($type)] ?? null;
    }
}
