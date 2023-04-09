<?php

namespace Kambo\LLamaCPP;

use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;

class Context
{
    private \FFI\CData $ctx;

    public function __construct(
        private LLamaCPPFFI $ffi,
        private ModelParameters $modelParameters,
    )
    {
        $lparams = $ffi->llama_context_default_params();

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
     * @return \FFI\CData
     */
    public function getCtx(): \FFI\CData
    {
        return $this->ctx;
    }
}
