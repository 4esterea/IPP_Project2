<?php

namespace IPP\Student\Types;
use IPP\Student\Exceptions\SourceException;

class Constant extends AbstractType
{
    function __construct(string $type, mixed $value)
    {
        $this->type = $type;

        if ($type == "int") {
            // decimal integer handling
            if (preg_match("/^[+-]?[0-9]+$/", $value)) {
                $this->value = (int)$value;
            // hexadecimal integer handling
            } else if (preg_match("/^[+-]?0x[0-9a-fA-F]*$/", $value)) {
                $this->value = hexdec($value);
            // octal integer handling
            } else if (preg_match("/^[+-]?0o[0-7]*$/", $value)) {
                $this->value = octdec($value);
            // binary integer handling
            } else if (preg_match("/^[+-]?0b[01]*$/", $value)) {
                $this->value = bindec($value);
            } else {
                throw new SourceException("Invalid value for int type");
            }
        } else if ($type == "bool") {
            if ($value == "true") {
                $this->value = true;
            } else if ($value == "false") {
                $this->value = false;
            } else {
                throw new SourceException("Invalid value for bool type");
            }
        } else if ($type == "string") {
            // string value handling using regex
            if (mb_ereg('^[\p{L}\p{N}\p{P}\p{S}]*(\\\\\d{3})*$', $value)) {

                // replace all escape sequences with their corresponding UTF-8 characters
                $value = preg_replace_callback('/\\\\(\d{3})/',
                    function ($matches) {
                        return chr(((int)$matches[1]));
                    }, $value);
                $this->value = (string)$value;
            } else {
                throw new SourceException("Invalid value for string type: " . $value);
            }
        } else if ($type == "nil") {
            if ($value == "nil") {
                $this->value = NULL;
            } else {
                throw new SourceException("Invalid value for nil type");
            }
        }
    }
}





