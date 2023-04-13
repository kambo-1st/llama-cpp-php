<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kambo\LLamaCPP\LLamaCPP;
use Kambo\LLamaCPP\Context;
use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Parameters\GenerationParameters;

$template = "You are a programmer, write PHP class that will add two numbers and print the result. Stop at class end.";
$context = Context::createWithParameter(new ModelParameters(__DIR__ .'/models/ggjt-model.bin'));
$llama = new LLamaCPP($context);
echo "Prompt: \033[0;32m".$template."\033[0m".PHP_EOL;

foreach ($llama->generate($template, new GenerationParameters(predictLength: 200)) as $token) {
    echo $token;
}
