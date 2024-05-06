<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\SourceException;

class InstructionBuilder
{
    /**
     * @param array<mixed> $operands
     */
    public function build(string $opcode, array $operands): AbstractInstruction
    {
        $className = "IPP\\Student\\Instructions\\i" . $opcode;
        if (class_exists($className)) {
            return new $className($operands);
     }
        throw new SourceException("Unknown opcode: " . $opcode);
    }
}