<?php

namespace IPP\Student\Types;


// Abstract class for types
abstract class AbstractType
{
    protected mixed $value;

    protected string $type;

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }
}