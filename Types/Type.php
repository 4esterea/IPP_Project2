<?php

namespace IPP\Student\Types;

use IPP\Student\Exceptions\SourceException;

class Type extends AbstractType
{
    public function __construct(string $type, string $value)
    {
        // check if type is valid
        if(!in_array($value, ['int', 'bool', 'string', 'nil'])) {
            throw new SourceException("Invalid type");
        }
        $this->type = $type;
        $this->value = $value;
    }
}