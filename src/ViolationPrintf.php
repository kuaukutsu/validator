<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

use JsonException;

/**
 * Class ViolationPrintf
 */
final class ViolationPrintf
{
    private int $flags;

    private ViolationCollection $collection;

    private const FLAG_FILTER_ATTRIBUTE_NAME = 1;

    public function __construct(ViolationCollection $collection, int $flags = 0)
    {
        $this->flags = $flags;
        $this->collection = $collection;
    }

    public function formatByAttributeName(string $name): self
    {
        return new self($this->collection->filter(static function (Violation $violation) use ($name): bool {
            return $violation->getAttributeName() === $name;
        }), $this->flags | self::FLAG_FILTER_ATTRIBUTE_NAME);
    }

    public function getFirstViolation(): string
    {
        return $this->collection->getFirstViolation();
    }

    public function getLastViolation(): string
    {
        return $this->collection->getLastViolation();
    }

    /**
     * @return array<string, non-empty-list<string>>|string[]
     */
    public function toArray(): array
    {
        $list = [];
        $attributeNameDisable = !$this->isFlagAttributeName();

        /** @var Violation $violation */
        foreach ($this->collection as $violation) {
            if ($attributeNameDisable && ($key = $violation->getAttributeName())) {
                $list[$key][] = (string)$violation;
                continue;
            }

            $list[] = (string)$violation;
        }

        return $list;
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    private function isFlagAttributeName(): bool
    {
        return ($this->flags & self::FLAG_FILTER_ATTRIBUTE_NAME) !== 0;
    }
}
