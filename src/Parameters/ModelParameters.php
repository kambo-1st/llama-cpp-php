<?php

namespace Kambo\LLamaCpp\Parameters;

final class ModelParameters
{
    /**
     * @param string $modelPath path to used model
     * @param int    $nCtx      text context
     * @param int    $nParts    -1 for default
     * @param int    $seed      RNG seed, 0 for random
     * @param bool   $f16KV     use fp16 for KV cache
     * @param bool   $logitsAll the llama_eval() call computes all logits, not just the last one
     * @param bool   $vocabOnly only load the vocabulary, no weights
     * @param bool   $useMlock  force system to keep model in RAM
     * @param bool   $embedding embedding mode only
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

    /**
     * @return int
     */
    public function getNCtx(): int
    {
        return $this->nCtx;
    }

    /**
     * @return int
     */
    public function getNParts(): int
    {
        return $this->nParts;
    }

    /**
     * @return int
     */
    public function getSeed(): int
    {
        return $this->seed;
    }

    /**
     * @return bool
     */
    public function isF16KV(): bool
    {
        return $this->f16KV;
    }

    /**
     * @return bool
     */
    public function isLogitsAll(): bool
    {
        return $this->logitsAll;
    }

    /**
     * @return bool
     */
    public function isVocabOnly(): bool
    {
        return $this->vocabOnly;
    }

    /**
     * @return bool
     */
    public function isUseMlock(): bool
    {
        return $this->useMlock;
    }

    /**
     * @return bool
     */
    public function isEmbedding(): bool
    {
        return $this->embedding;
    }

    public function getModelPath(): string
    {
        return $this->modelPath;
    }
}
