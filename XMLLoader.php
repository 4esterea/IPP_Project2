<?php
namespace IPP\Student;

use DOMDocument;
use IPP\Student\Exceptions\SourceException;
use IPP\Student\Instructions\AbstractInstruction;
use IPP\Student\Instructions\InstructionBuilder;
use IPP\Student\Types\OperandBuilder;

class XMLLoader
{
    /**
     * @var AbstractInstruction[]
     */
    private array $instructionList = [];

    public function run(DOMDocument $dom, ProgramFlow $programFlow, Frames $frames, Stack $stack): void
    {
        // check if the root element is program
        $program = $dom->getElementsByTagName('program')->item(0);

        // check if the program element contains only instruction elements
        foreach ($program->childNodes as $childNode) {
            if ($childNode->nodeType === XML_ELEMENT_NODE && $childNode->nodeName !== 'instruction') {
                throw new SourceException("Unexpected element: " . $childNode->nodeName);
            }
        }
        $instructions = $dom->getElementsByTagName('instruction');
        $instructionList = [];

        // create instances of OperandBuilder and InstructionBuilder
        $operandBuilder = new OperandBuilder();
        $instructionBuilder = new InstructionBuilder();

        // loop through the instruction elements and build the instructions
        foreach ($instructions as $instruction) {

            $opcode = strtoupper(trim($instruction->getAttribute('opcode')));
            $order = trim($instruction->getAttribute('order'));

            // check if the order attribute is valid
            if ($order == '' || $order < 1 || !ctype_digit($order)) {
                throw new SourceException("Invalid order attribute");
            }

            // check the opcode attribute existence
            if ($opcode == '') {
                throw new SourceException("Missing opcode in instruction with order: " . $order);
            }
            $operands = [];

            // check if the instruction element contains only arg elements
             foreach ($instruction->childNodes as $childNode) {
                 if ($childNode->nodeType === XML_ELEMENT_NODE && !preg_match('/^arg\d+$/', $childNode->nodeName)) {
                     throw new SourceException("Unexpected element: " . $childNode->nodeName);
                 }
             }

            // loop through the arg elements and build the operands
            for ($i = 1; $i <= 3; $i++) { // assuming maximum 3 arguments
                $argument = $instruction->getElementsByTagName('arg' . $i)->item(0);

                if ($argument && $argument->nodeType === XML_ELEMENT_NODE) {
                    $type = $argument->getAttribute('type');
                    $value = trim($argument->nodeValue);

                    // build the operand
                    $operands[] = $operandBuilder->build($type, $value);
                } else {
                    // check if the argument is missing while the next argument is present
                    if ($instruction->getElementsByTagName('arg' . ($i + 1))->item(0) || $instruction->getElementsByTagName('arg' . ($i + 2))->item(0)) {
                        throw new SourceException("Missing argument arg" . $i . "while arg" . ($i + 1) . " is present in instruction with order: " . $order);
                    }
                }
            }

            // build the instruction and add it to the instruction list if the order is unique
            if (empty($instructionList[$order])){
                $instructionList[$order] = $instructionBuilder->build($opcode, $operands);
            } else {
                throw new SourceException("Duplicate order attribute");
            }
        }
        // sort the instruction list by order and reindex the array
        ksort($instructionList);
        $instructionList = array_values($instructionList);

        // set the instruction list
        $this->instructionList = $instructionList;
    }
    /**
     * @return AbstractInstruction[]
     */
    public function getInstructionList(): array
    {
        return $this->instructionList;
    }
}