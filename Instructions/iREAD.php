<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iREAD extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['type']]);
        $type = $this->getOperands()[1]->getValue();
        if ($type == 'int') {
            $value = $in->readInt();
            if (preg_match('/^[-+]?((0b[01]+)|(0o[0-7]+)|(0x[0-9a-fA-F]+)|[0-9]+)$/', (string)$value)) {
                $result = new StackElement((int)$value, 'int');
            } else {
                $result = new StackElement('nil', 'nil');
            }
        } elseif ($type == 'bool') {
            $value = $in->readBool();
            if ($value == 'true') {
                $result = new StackElement(true, 'bool');
            } else if ($value == 'false') {
                $result = new StackElement(false, 'bool');
            } else {
                $result = new StackElement('nil', 'bool');
            }
        } else {
            $value = $in->readString();
            $result = new StackElement($value, 'string');
        }
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}