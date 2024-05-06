<?php

namespace IPP\Student\Types;

use IPP\Student\Exceptions\LabelException;
use IPP\Student\Exceptions\SourceException;
use IPP\Student\ProgramFlow;


class Label extends AbstractType
{

    public function __construct(string $name)
    {
        if (preg_match('/\b[a-zA-Z_\-$&%*!?][a-zA-Z_\-$&%*!?0-9]*$/', $name)) {
            $this->type = $name;
            $this->value = NULL;
        } else {
            throw new SourceException("Label name error");
        }
    }

    // renamed getType() method from AbstractType for better readability
    public function getName(): string
    {
        return $this->type;
    }

    // renamed getValue() method from AbstractType for better readability
    public function getPointer(): ?int
    {
        return $this->value;
    }

    public function setPointer(int $pointer): void
    {
        $this->value = $pointer;
    }

    // method to check if label exists
    public function checkExistence(ProgramFlow $programFlow): void
    {
        if (empty($programFlow->getLabels()[$this->getName()])) {
            throw new LabelException("Label not found");
        }
    }
}