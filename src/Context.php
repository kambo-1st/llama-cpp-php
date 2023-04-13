<?php

namespace Kambo\LLamaCPP;

use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;
use FFI\CData;

final class Context
{
    private CData $ctx;

    public function __construct(
        private LLamaCPPFFI $ffi,
        private readonly ModelParameters $modelParameters,
    ) {
        $lparams = $ffi->llama_context_default_params();

        $lparams->n_ctx = $modelParameters->getNCtx();
        $lparams->n_parts = $modelParameters->getNParts();
        $lparams->seed = $modelParameters->getSeed();
        $lparams->f16_kv = $modelParameters->isF16KV();
        $lparams->logits_all = $modelParameters->isLogitsAll();
        $lparams->vocab_only = $modelParameters->isVocabOnly();
        $lparams->use_mlock = $modelParameters->isUseMlock();
        $lparams->embedding = $modelParameters->isEmbedding();

        $this->ctx = $ffi->llama_init_from_file($modelParameters->getModelPath(), $lparams);
    }

    public static function createWithParameter(
        ModelParameters $modelParameters,
    ): self {
        $ffi = LLamaCPPFFI::getInstance();

        return new self($ffi, $modelParameters);
    }

    public function __destruct()
    {
        $this->ffi->llama_free($this->ctx);
    }

    /**
     * @return CData
     */
    public function getCtx(): CData
    {
        return $this->ctx;
    }
}
