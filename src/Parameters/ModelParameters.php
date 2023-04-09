<?php

namespace Kambo\LLamaCpp\Parameters;

class ModelParameters
{
    /**
     *
     *
     * int n_ctx; // text context
     * int n_parts; // -1 for default
     * int seed; // RNG seed, 0 for random
     * bool f16_kv; // use fp16 for KV cache
     * bool logits_all; // the llama_eval() call computes all logits, not just the last one
     * bool vocab_only; // only load the vocabulary, no weights
     * bool use_mlock; // force system to keep model in RAM
     * bool embedding; // embedding mode only
     */
    public function __construct(
        private string $modelPath,
        private int $nCtx = 1024,
        private int $nParts = -1,
        private int $seed = 0,
        private bool $f16KV = false,
        private bool $logitsAll = false,
        private bool $vocabOnly = false,
        private bool $useMlock = false,
        private bool $embedding = false,
    ) {

    }

    public function getModelPath(): string
    {
        return $this->modelPath;
    }
}
