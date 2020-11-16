<?php
declare(strict_types=1);

namespace kuaukutsu\validator;

/**
 * Class Violation
 */
final class Violation
{
    private string $message;

    /**
     * @var array<string, mixed>
     */
    private array $parameters;

    private ?string $attributeName = null;

    /**
     * Violation constructor.
     * @param string $message
     * @param array<string, mixed> $parameters
     */
    public function __construct(string $message, array $parameters = [])
    {
        $this->message = $message;
        $this->parameters = $parameters;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParameter(string $name, $value): self
    {
        $result = clone $this;
        $result->parameters[$name] ??= $value;

        return $result;
    }

    public function setAttributeName(string $name): self
    {
        $result = clone $this;
        $result->attributeName = $name;

        return $result;
    }

    /**
     * @return string|null
     */
    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function __toString(): string
    {
        return $this->translateMessage($this->message, $this->parameters);
    }

    /**
     * @param string $message
     * @param array<string, mixed> $arguments
     * @return string
     */
    private function translateMessage(string $message, array $arguments = []): string
    {
        return $this->formatMessage($message, $arguments);
    }

    /**
     * @param string $message
     * @param array<string, mixed> $arguments
     * @return string
     */
    private function formatMessage(string $message, array $arguments = []): string
    {
        $replacements = [];
        foreach ($arguments as $key => $value) {
            $replacements['{' . $key . '}'] = is_scalar($value) ? $value : gettype($value);
        }

        return strtr($message, $replacements);
    }
}
