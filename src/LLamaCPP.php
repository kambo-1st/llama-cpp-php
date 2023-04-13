<?php

namespace Kambo\LLamaCPP;

use Kambo\LLamaCPP\Parameters\GenerationParameters;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;

final class LLamaCPP
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

    public function generate(string $prompt, ?GenerationParameters $generation=null): \Generator
    {
        if ($generation === null) {
            $generation = new GenerationParameters();
        }

        $input = $this->ffi->newArray("llama_token", strlen($prompt));

        $nOfTok = $this->ffi->llama_tokenize($this->context->getCtx(), $prompt, $input, strlen($prompt), true);

        for ($i = 0; $i < $nOfTok; $i++) {
            $this->ffi->llama_eval($this->context->getCtx(), $input + $i, 1, $i, 10);
        }

        $eosToken = $this->ffi->llama_token_eos();
        $desiredNumberOfTokens = $generation->getPredictLength();
        for ($i = 0; $i < $desiredNumberOfTokens; $i++) {

            $id = $this->ffi->llama_sample_top_p_top_k(
                $this->context->getCtx(),
                null,
                0,
                $generation->getTopP(),
                $generation->getTopK(),
                $generation->getTemperature(),
                $generation->getRepeatPenalty(),
            );

            if ($id == $eosToken) {
                break;
            }

            $token = $this->ffi->new("llama_token");
            $token->cdata = $id;

            $nOfTok++;

            $prediction = $this->ffi->llama_token_to_str($this->context->getCtx(), $id);

            yield $prediction;
            $this->ffi->llama_eval($this->context->getCtx(), $this->ffi->addr($token), 1, $nOfTok, 10);
        }
    }

    public function generateAll(string $prompt, ?GenerationParameters $generation=null) : string
    {
        $tokens = iterator_to_array(
            $this->generate($prompt, $generation)
        );

        return implode('', $tokens);
    }
}
