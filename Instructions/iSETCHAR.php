<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iSETCHAR extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['int'], ['string']]);
        if ($this->getOperands()[0]->getType() != 'string') {
            throw new StringException("Variable should have string type");
        }
        $var = $this->getOperands()[0];
        $pos = $this->getOperands()[1];
        $char = $this->getOperands()[2];
        if ($pos->getValue() < 0 || $pos->getValue() >= strlen($var->getValue())) {
            throw new StringException("Invalid string index");
        }
        $result = new StackElement(substr_replace($var->getValue(), $char->getValue(), $pos->getValue(), 1), 'string');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}