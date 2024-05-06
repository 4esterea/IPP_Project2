<?php

namespace IPP\Student\Types;

use IPP\Student\Exceptions\SourceException;
use IPP\Student\Exceptions\VariableException;
use IPP\Student\Frames;

class Variable extends AbstractType
{
    private string $frame;

    private string $name;

    function __construct(string $value)
    {
        // check if variable is valid using regex
        if (preg_match('/\b(GF|LF|TF)@[a-zA-Z_\-$&%*!?][a-zA-Z_\-$&%*!?0-9]*$/', $value)) {
            $content = explode('@', $value);

            // initialize frame, name, type and value
            $this->frame = $content[0];
            $this->name = $content[1];
            $this->type = "nil";
            $this->value = "nil";
        } else {
            throw new SourceException("Variable name error");
        }
    }

    public function getFrame(): string
    {
        return $this->frame;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    // method to update variable value and type from given frame
    public function update(Frames $frames): void
    {
        $this->checkExistence($frames);
        $this->setValue($frames->getFrame($this->getFrame())[$this->getName()]->getValue());
        $this->setType($frames->getFrame($this->getFrame())[$this->getName()]->getType());
    }

    // method to check if variable exists in given frame
    public function checkExistence(Frames $frames): void
    {
        if (empty($frames->getFrame($this->getFrame())[$this->getName()])) {
            throw new VariableException("Variable does not exist");
        }
    }
}