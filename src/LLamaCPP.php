<?php

namespace Kambo\LLamaCPP;

use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Parameters\GenerationParameters;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;

class LLamaCPP
{
    public function __construct(
        private Context $context,
        private ?EventDispatcherInterface $eventDispatcher=null,
        private ?LLamaCPPFFI $ffi = null,
    )
    {
        if ($ffi === null) {
            $this->ffi = LLamaCPPFFI::getInstance();
        }
    }

    public function generate(string $prompt, GenerationParameters $generation)
    {

    }

    public function generateAll(string $prompt, ?GenerationParameters $generation=null) : string
    {
        $input = $this->ffi->new("llama_token[".strlen($prompt)."]");

        $nOfTok = $this->ffi->llama_tokenize($this->context->getCtx(), $prompt, $input, strlen($prompt), true);

        for ($i = 0; $i < $nOfTok; $i++) {
            $this->ffi->llama_eval($this->context->getCtx(), $input + $i, 1, $i, 10);
        }

        //echo "Prompt: " . $prompt.PHP_EOL;
        $tokenCount = $nOfTok;
        $result = '';
        for ($i = 0; $i < 300; $i++) {

            $id = $this->ffi->llama_sample_top_p_top_k($this->context->getCtx(), null, 0, 40, 0.8, 0.2, 1/0.85);

            // TODO: break here if EOS

            $token = $this->ffi->new("llama_token");
            $token->cdata = $id;

            $tokenCount++;

            $prediction = $this->ffi->llama_token_to_str($this->context->getCtx(), $id);
            $result .= $prediction;

            // eval next token
            $this->ffi->llama_eval($this->context->getCtx(), $this->ffi->addr($token), 1, $tokenCount, 10);
        }

        return $result;
    }
}
