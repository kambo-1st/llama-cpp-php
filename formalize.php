<?php

use Kambo\LLamaCPP\Native\LLamaCPPFFI;

require_once __DIR__ . '/vendor/autoload.php';


$llmaFFI = LLamaCPPFFI::getInstance();
//$llmaFFI = FFI::load('llama-ffi.h');

$prompt = "Why is PHP best language for web?";
$prompt = "You are a programmer, write PHP class that will add two numbers and print the result. Stop at class end.";

$lparams = $llmaFFI->llama_context_default_params();

$ctx = $llmaFFI->llama_init_from_file("./models/gpt4all-7B/gpt4all-lora-quantized-new.bin", $lparams);

$input = $llmaFFI->new("llama_token[".strlen($prompt)."]");

$nOfTok = $llmaFFI->llama_tokenize($ctx, $prompt, $input, strlen($prompt), true);

//var_dump($nOfTok);

for ($i = 0; $i < $nOfTok; $i++) {
    $llmaFFI->llama_eval($ctx, $input + $i, 1, $i, 10);
}

echo "Prompt: " . $prompt.PHP_EOL;
$tokenCount = $nOfTok;
$result = '';
for ($i = 0; $i < 300; $i++) {

    $id = $llmaFFI->llama_sample_top_p_top_k($ctx, null, 0, 40, 0.8, 0.2, 1/0.85);

    // TODO: break here if EOS

    $token = $llmaFFI->new("llama_token");
    $token->cdata = $id;

    $tokenCount++;

    $prediction = $llmaFFI->llama_token_to_str($ctx, $id);
    $result .= $prediction;
    echo $prediction;

    // eval next token
    $llmaFFI->llama_eval($ctx, FFI::addr($token), 1, $tokenCount, 10);
}

$llmaFFI->llama_free($ctx); // cleanup
