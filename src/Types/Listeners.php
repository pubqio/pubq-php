<?php

interface ErrorListener
{
    public function __invoke(?ErrorInfo $error): void;
}
