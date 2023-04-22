<?php

namespace Kambo\Tests\LLamaCPP;

use PHPUnit\Framework\TestCase;
use Kambo\LLamaCPP\Parameters\ModelParameters;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;
use Kambo\LLamaCPP\Context;
use FFI\CData;
use FFI;

class ContextTest extends TestCase
{
    private LLamaCPPFFI $ffiMock;
    private ModelParameters $modelParametersMock;
    private FFI $ffi;

    protected function setUp(): void
    {
        $this->ffiMock = $this->getMockBuilder(LLamaCPPFFI::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->modelParametersMock = $this->getMockBuilder(ModelParameters::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ffi = FFI::cdef('struct llama_context; struct llama_context_params {
    int n_ctx; // text context
    int n_parts; // -1 for default
    int seed; // RNG seed, 0 for random
    bool f16_kv; // use fp16 for KV cache
    bool logits_all; // the llama_eval() call computes all logits, not just the last one
    bool vocab_only; // only load the vocabulary, no weights
    bool use_mlock; // force system to keep model in RAM
    bool embedding; // embedding mode only
};');
    }

    public function testConstruct()
    {
        $this->ffiMock->method('llama_context_default_params')
            ->willReturn($this->ffi->new('struct llama_context_params'));

        $this->modelParametersMock->method('getNCtx')
            ->willReturn(512);
        $this->modelParametersMock->method('getNParts')
            ->willReturn(16);
        $this->modelParametersMock->method('getSeed')
            ->willReturn(42);
        $this->modelParametersMock->method('isF16KV')
            ->willReturn(false);
        $this->modelParametersMock->method('isLogitsAll')
            ->willReturn(true);
        $this->modelParametersMock->method('isVocabOnly')
            ->willReturn(false);
        $this->modelParametersMock->method('isUseMlock')
            ->willReturn(false);
        $this->modelParametersMock->method('isEmbedding')
            ->willReturn(true);
        $this->modelParametersMock->method('getModelPath')
            ->willReturn('/path/to/model');

        $this->ffiMock->method('llama_init_from_file')
            ->willReturn(FFI::new('int'));

        $context = new Context($this->ffiMock, $this->modelParametersMock);

        $this->assertInstanceOf(Context::class, $context);
    }

    public function testCreateWithParameter()
    {
        $this->ffiMock->method('llama_context_default_params')
            ->willReturn($this->ffi->new('struct llama_context_params'));

        $this->modelParametersMock->method('getNCtx')
            ->willReturn(512);

        $this->ffiMock->method('llama_init_from_file')
            ->willReturn(FFI::new('int'));

        $context = Context::createWithParameter($this->modelParametersMock, $this->ffiMock);

        $this->assertInstanceOf(Context::class, $context);
    }

    public function testGetCtx()
    {
        $this->ffiMock->method('llama_context_default_params')
            ->willReturn($this->ffi->new('struct llama_context_params'));

        $cdataStub = FFI::new('int');
        $this->ffiMock->method('llama_init_from_file')
            ->willReturn($cdataStub);

        $context = new Context($this->ffiMock, $this->modelParametersMock);

        $this->assertSame(
            $cdataStub,
            $context->getCtx()
        );
    }

    public function testDestruct()
    {
        $this->ffiMock->method('llama_context_default_params')
            ->willReturn($this->ffi->new('struct llama_context_params'));

        $this->ffiMock->method('llama_init_from_file')
            ->willReturn(FFI::new('int'));

        $context = new Context($this->ffiMock, $this->modelParametersMock);

        // Expect llama_free to be called once with the given CData stub
        $this->ffiMock->expects($this->once())
            ->method('llama_free')
            ->with(FFI::new('int'));

        // Trigger __destruct by unsetting the context
        unset($context);
    }
}
