<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kambo\LLamaCPP\LLamaCPP;
use Kambo\LLamaCPP\Context;
use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Parameters\GenerationParameters;

$template = "You are a programmer, write PHP class that will add two numbers and print the result. Stop at class end.";
$context = Context::createWithParameter(
    new ModelParameters(
        modelPath:__DIR__ .'/models/ggjt-model.bin',
        embedding: true,
    )
);
$llama = new LLamaCPP($context);

$embeddings = $llama->createEmbedding($template);

var_dump($embeddings);
