<?php
declare(strict_types=1);

namespace kuaukutsu\validator\tests\data;

final class Entity
{
    public int $id;

    public string $name;

    public bool $isActive;

    public function __construct(int $id, string $name, bool $isActive = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isActive = $isActive;
    }
}
