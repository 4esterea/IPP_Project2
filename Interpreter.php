<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\OperandValueException;
use IPP\Student\Exceptions\SourceException;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Instructions\AbstractInstruction;
use IPP\Student\Instructions\iEXIT;
use IPP\Student\Instructions\iLABEL;
use IPP\Student\Types\Variable;

class Interpreter extends AbstractInterpreter
{
    /**
     * @var AbstractInstruction[]
     */
    private $instructionList;

    /**
     * @var Frames
     */
    private $frames;

    /**
     * @var Stack
     */
    private $stack;

    /**
     * @var ProgramFlow
     */
    private $programFlow;

    public function execute(): int
    {
        // get the DOM document using ipp-core
        $dom = $this->source->getDOMDocument();

        // create instances of Frames, ProgramFlow, Stack and XMLLoader
        $this->frames = new Frames();
        $this->programFlow = new ProgramFlow();
        $this->stack = new Stack();
        $XMLLoader = new XMLLoader();

        // get the instruction list from the XMLLoader
        $XMLLoader->run($dom, $this->programFlow, $this->frames, $this->stack);
        $this->instructionList = $XMLLoader->getInstructionList();

        $this->setLabels();

        // loop through the instruction list and execute each instruction
        for ($this->programFlow->getInstructionPointer();//
        $this->programFlow->getInstructionPointer() < count($this->instructionList);//
        $this->programFlow->incrementInstructionPointer()) {
            $instruction = $this->instructionList[$this->programFlow->getInstructionPointer()];
            $rc = $instruction->execute($this->frames, $this->stack, $this->programFlow, $this->stdout, $this->stderr, $this->input);
            if($this->instructionList[$this->programFlow->getInstructionPointer()] instanceof iEXIT) return $rc;
        }
        return 0;
    }

    // function that sets the labels in the program flow
    private function setLabels(): void
    {
        for ($i = 0; $i < count($this->instructionList); $i++) {
            if ($this->instructionList[$i] instanceof iLABEL) {
                $label = $this->instructionList[$i]->getOperands()[0];
                $label->setPointer($i);
                $this->programFlow->setLabel($label);
            }
        }
    }


//    function that updates the value of the variable in the instruction to the value of the variable in the frame

}