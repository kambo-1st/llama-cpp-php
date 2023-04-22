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

    public function llama_context_default_params(): CData
    {
        return $this->fii->llama_context_default_params();
    }

    /**
     * Allocate (almost) all memory needed for the model.
     *
     * @param string $path
     * @param CData  $params
     *
     * @return CData Return NULL on failure
     */
    public function llama_init_from_file(string $path, CData $params): CData
    {
        return $this->fii->llama_init_from_file($path, $params);
    }

    /**
     * Convert the provided text into tokens.
     * The tokens pointer must be large enough to hold the resulting tokens.
     *
     * @param CData  $ctx
     * @param string $text
     * @param CData  $tokens
     * @param int    $maxTokens
     * @param bool   $addEOS
     *
     * @return int the number of tokens on success, no more than n_max_tokens, or -1 on error
     */
    public function llama_tokenize(CData $ctx, string $text, CData $tokens, int $maxTokens, bool $addEOS): int
    {
        return $this->fii->llama_tokenize($ctx, $text, $tokens, $maxTokens, $addEOS);
    }

    /**
     * Run the llama inference to obtain the logits and probabilities for the next token.
     *
     * @param CData $ctx
     * @param CData $tokens the provided batch of new tokens to process
     * @param int   $nOfTokens the provided batch of new tokens to process
     * @param int   $nOfPastTokens the number of tokens to use from previous eval calls
     * @param int   $nOfThreads The number of threads to use for the inference
     *
     * @return int
     */
    public function llama_eval(CData $ctx, CData $tokens, int $nOfTokens, int $nOfPastTokens, int $nOfThreads): int
    {
        return $this->fii->llama_eval($ctx, $tokens, $nOfTokens, $nOfPastTokens, $nOfThreads);
    }

    /**
     * Sample top-k and top-p from the logits.
     *
     * @param CData      $ctx
     * @param CData|null $lastNTokens
     * @param int        $lastNTokensSize
     * @param float      $topP
     * @param float      $topK
     * @param float      $temperature
     * @param float      $repeatPenalty
     *
     * @return int
     */
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

    /**
     * Token Id -> String. Uses the vocabulary in the provided context
     *
     * @param CData $ctx
     * @param int   $id
     *
     * @return string
     */
    public function llama_token_to_str(CData $ctx, int $id): string
    {
        return $this->fii->llama_token_to_str($ctx, $id);
    }

    /**
     * Frees all allocated memory
     *
     * @param CData $ctx
     *
     * @return void
     */
    public function llama_free(CData $ctx): void
    {
        $this->fii->llama_free($ctx);
    }

    public function llama_token_eos()
    {
        return $this->fii->llama_token_eos();
    }
}
