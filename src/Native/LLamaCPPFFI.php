<?php

namespace Kambo\LLamaCPP\Native;

use FFI;
use FFI\CData;
use FFI\CType;

use function file_get_contents;

/**
 * Wrapper for llama-ffi.h
 *
 * @package Kambo\LLamaCPP\Native
 */
class LLamaCPPFFI
{
    private static ?LLamaCPPFFI $instance = null;

    public function __construct(private FFI $fii)
    {
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance == null) {
            self::$instance = self::create() ;
        }

        return self::$instance;
    }

    /**
     * @param ?LocateLib $locator
     *
     * @return static
     */
    public static function create(?LocateLib $locator = null): self
    {
        if ($locator === null) {
            $locator = new Locator();
        }

        $path = $locator->getLibraryPath();

        return self::createWithLibraryInPath($path);
    }

    /**
     * @param string $path
     *
     * @return static
     */
    public static function createWithLibraryInPath(string $path): self
    {
        $FFI = FFI::cdef(file_get_contents(__DIR__ . '/llama-ffi.h'), $path);

        return new self($FFI);
    }

    /**
     * Method that creates an arbitrary C structure.
     *
     * @param string $type
     *
     * @return CData|null
     */
    public function new(string $type): ?CData
    {
        return $this->fii->new($type);
    }

    /**
     * Method that creates a C array of specific type.
     *
     * @param string $type
     *
     * @return CData|null
     */
    public function newArray(string $type, int $size): ?CData
    {
        return $this->fii->new($type . '[' . $size . ']');
    }

    /**
     * Returns C pointer to the given C data structure. The pointer is
     * not "owned" and won't be free. Anyway, this is a potentially
     * unsafe operation, because the life-time of the returned pointer
     * may be longer than life-time of the source object, and this may
     * cause dangling pointer dereference (like in regular C).
     *
     * @param CData $ptr
     *
     * @return CData
     */
    public function addr(CData $ptr): CData
    {
        return FFI::addr($ptr);
    }

    // generate wrapper method for llama_context_default_params
    public function llama_context_default_params(): CData
    {
        return $this->fii->llama_context_default_params();
    }

    // generate wrapper method for llama_init_from_file
    public function llama_init_from_file(string $path, CData $params): CData
    {
        return $this->fii->llama_init_from_file($path, $params);
    }

    // generate wrapper method for llama_tokenize
    public function llama_tokenize(CData $ctx, string $text, CData $tokens, int $maxTokens, bool $addEOS): int
    {
        return $this->fii->llama_tokenize($ctx, $text, $tokens, $maxTokens, $addEOS);
    }

    // generate wrapper method for llama_eval
    public function llama_eval(CData $ctx, CData $tokens, int $nOfTokens, int $position, int $nOfSamples): void
    {
        $this->fii->llama_eval($ctx, $tokens, $nOfTokens, $position, $nOfSamples);
    }

    public function llama_sample_top_p_top_k(
        CData $ctx,
        ?CData $lastNTokens,
        int $lastNTokensSize,
        float $topP,
        float $topK,
        float $temperature,
        float $repeatPenalty
    ): int {
        return $this->fii->llama_sample_top_p_top_k(
            $ctx,
            $lastNTokens,
            $lastNTokensSize,
            $topP,
            $topK,
            $temperature,
            $repeatPenalty
        );
    }

    public function llama_token_to_str(CData $ctx, int $id): string
    {
        return $this->fii->llama_token_to_str($ctx, $id);
    }

    public function llama_free(CData $ctx): void
    {
        $this->fii->llama_free($ctx);
    }

    public function llama_token_eos()
    {
        return $this->fii->llama_token_eos();
    }
}
