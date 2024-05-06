<?php

namespace IPP\Student;

use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Types\Variable;

class Frames {
    /**
     * @var Variable[]
     */
    private array $globalFrame = [];
    /**
     * @var Variable[][]
     */
    private array $frameStack = [];
    /**
     * @var Variable[]|null
     */
    private ?array $tempFrame = null;

    public function __construct() {
        $this->globalFrame = array();
        $this->frameStack = array();
        $this->tempFrame = null;
    }

    public function createFrame(): void {
        $this->tempFrame = array();
    }

    public function pushFrame(): void {
        $this->frameStack[] = $this->getTempFrame();
        $this->tempFrame = null;
    }

    public function popFrame(): void {
        if (empty($this->frameStack)) {
            throw new FrameException;
        }
        $this->tempFrame = array_pop($this->frameStack);
    }

    /**
     * @return Variable[]
     */
    public function getFrame(string $frame): array {
        switch ($frame) {
            case 'GF':
                return $this->getGlobalFrame();
            case 'LF':
                return $this->getLocalFrame();
            case 'TF':
                return $this->getTempFrame();
            default:
                throw new FrameException;
        }
    }
    /**
     * @return Variable[]
     */
    private function getGlobalFrame(): array {
        return $this->globalFrame;
    }
    /**
     * @return Variable[]
     */
    private function getTempFrame(): array {
        if ($this->tempFrame === null) {
            throw new FrameException;
        }
        return $this->tempFrame;
    }
    /**
     * @return Variable[]
     */
    private function getLocalFrame(): array {
        if (empty($this->frameStack)) {
            throw new FrameException;
        }
        return $this->frameStack[count($this->frameStack) - 1];
    }

    public function defineVar(Variable $var): void {
        $frame = $var->getFrame();
        switch ($frame) {
            case 'GF':
                $name = $var->getName();
                if (!empty($this->globalFrame[$name])){
                    throw new SemanticException("Variable already defined");
                }
                $this->globalFrame[$name] = $var;
                break;
            case 'LF':
                $name = $var->getName();
                $localFrame = &$this->frameStack[count($this->frameStack) - 1];
                if (!empty($localFrame[$name])){
                    throw new SemanticException("Variable already defined");
                }
                $localFrame[$name] = $var;
                break;
            case 'TF':
                $name = $var->getName();
                if ($this->tempFrame === null) {
                    throw new FrameException("Temp frame not created");
                }
                if (!empty($this->tempFrame[$name])){
                    throw new SemanticException("Variable already defined");
                }
                $this->tempFrame[$name] = $var;
                break;
            default:
                throw new FrameException;
        }
    }

    public function setFrameValue(Variable $var, StackElement $const): void {
        $frame = $var->getFrame();
        switch ($frame) {
            case 'GF':
                $this->setGlobalFrameValue($var, $const);
                break;
            case 'LF':
                $this->setLocalFrameValue($var, $const);
                break;
            case 'TF':
                $this->setTempFrameValue($var, $const);
                break;
            default:
                throw new FrameException;
        }
    }

    private function setGlobalFrameValue(Variable $var, StackElement $const): void {
        if (empty($this->globalFrame[$var->getName()])) {
            throw new SemanticException("Variable not defined");
        }
        $this->globalFrame[$var->getName()]->setValue($const->getValue());
        $this->globalFrame[$var->getName()]->setType($const->getType());
    }

    private function setLocalFrameValue(Variable $var, StackElement $const): void {
        if (empty($this->frameStack[count($this->frameStack) - 1][$var->getName()])) {
            throw new SemanticException("Variable not defined");
        }
        $this->getFrame($var->getFrame())[$var->getName()]->setValue($const->getValue());
        $this->getFrame($var->getFrame())[$var->getName()]->setType($const->getType());
    }

    private function setTempFrameValue(Variable $var, StackElement $const): void {
        if (empty($this->getTempFrame()[$var->getName()])) {
            throw new SemanticException("Variable not defined");
        }
        $this->getFrame($var->getFrame())[$var->getName()]->setValue($const->getValue());
        $this->getFrame($var->getFrame())[$var->getName()]->setType($const->getType());
    }

    // method for printing frames for debugging purposes
    public function printFrames(OutputWriter $stream): void
    {
        $stream->writeString("\n[Global frame]:\n\n");
        foreach ($this->globalFrame as $var) {
            $stream->writeString($var->getName() . " " . $var->getType() . " " . $var->getValue() . "\n");
        }
        $stream->writeString("\n[Local frame]:\n\n");
        if (empty($this->frameStack)) {
            $stream->writeString("Empty\n");
        } else {
            foreach ($this->getLocalFrame() as $var) {
                $stream->writeString($var->getName() . " " . $var->getType() . " " . $var->getValue() . "\n");
            }
        }
        $stream->writeString("\n[Temp frame]:\n\n");
        if ($this->tempFrame == null) {
            $stream->writeString("Not Initialized\n\n");
        } else {
            if (empty($this->tempFrame)) {
                $stream->writeString("Empty\n\n");
            }
            foreach ($this->getTempFrame() as $var) {
                $stream->writeString($var->getName() . " " . $var->getType() . " " . $var->getValue() . "\n");
            }
        }
    }
}