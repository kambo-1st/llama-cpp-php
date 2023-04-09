<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kambo\LLamaCPP\LLamaCPP;
use Kambo\LLamaCPP\Context;
use Kambo\LLamaCPP\Parameters\ModelParameters;

$context = Context::createWithParameter(new ModelParameters('./models/gpt4all-7B/gpt4all-lora-quantized-new.bin'));
$llama = new LLamaCPP($context);
echo $llama->generateAll("You are a programmer, write PHP class that will add two numbers and print the result. Stop at class end.");
