<?php

namespace IPP\Student\Types;

use IPP\Student\Exceptions\SourceException;

class OperandBuilder
{
    // method to create operand of given type and value
    public function build(string $type, mixed $value): AbstractType
    {
        if ($type == 'int' || $type == 'bool' || $type == 'string' || $type == 'nil') {
            return new Constant($type, $value);
        } elseif ($type == 'var') {
            return new Variable($value);
        } elseif ($type == 'label') {
            return new Label($value);
        } elseif ($type == 'type') {
            return new Type($type, $value);
        } else {
            throw new SourceException("Invalid type");
        }
    }
}