<?php

class ErrorInfo
{
    public $code; // number | null
    public $statusCode; // ?number
    public $message; // ?string
    public $cause; // ?Error
    public $href; // ?string

    public function __construct($code = null, $statusCode = null, $message = null, $cause = null, $href = null)
    {
        $this->code = $code;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->cause = $cause;
        $this->href = $href;
    }
}
