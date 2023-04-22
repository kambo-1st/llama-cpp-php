<?php

namespace Kambo\Tests\LLamaCPP;

use PHPUnit\Framework\TestCase;
use Kambo\LLamaCPP\Parameters\GenerationParameters;
use Kambo\LLamaCPP\Context;
use Kambo\LLamaCPP\LLamaCPP;
use Kambo\LLamaCPP\Native\LLamaCPPFFI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Generator;
use FFI;

use function iterator_to_array;

class LLamaCPPTest extends TestCase
{
    private Context $contextMock;
    private EventDispatcherInterface $eventDispatcherMock;
    private LLamaCPPFFI $ffiMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)
            ->getMock();

        $this->ffiMock = $this->getMockBuilder(LLamaCPPFFI::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGenerate()
    {
        $this->contextMock->method('getCtx')
            ->willReturn(FFI::new('int'));

        $this->ffiMock->method('newArray')
            ->willReturn(FFI::new('int[100]'));

        $this->ffiMock->method('llama_tokenize')
            ->willReturn(1);

        $this->ffiMock->method('llama_eval');

        $this->ffiMock->method('llama_token_eos')
            ->willReturn(0);

        $this->ffiMock->method('llama_sample_top_p_top_k')
            ->willReturn(1);

        $this->ffiMock->method('new')
            ->willReturn(FFI::new('int'));

        $this->ffiMock->method('llama_token_to_str')
            ->willReturn('test');

        $this->ffiMock->method('addr')
            ->willReturn(FFI::new('int'));

        $llamaCPP = new LLamaCPP($this->contextMock, $this->eventDispatcherMock, $this->ffiMock);

        $result = iterator_to_array($llamaCPP->generate('test', new GenerationParameters(predictLength: 1)));

        $this->assertInstanceOf(Generator::class, $llamaCPP->generate('test', new GenerationParameters(predictLength: 1)));
        $this->assertEquals(['test'], $result);
    }

    public function testGenerateAll()
    {
        $this->contextMock->method('getCtx')
            ->willReturn(FFI::new('int'));

        $this->ffiMock->method('newArray')
            ->willReturn(FFI::new('int[100]'));

        $this->ffiMock->method('llama_tokenize')
            ->willReturn(1);

        $this->ffiMock->method('llama_eval');

        $this->ffiMock->method('llama_token_eos')
            ->willReturn(0);

        $this->ffiMock->method('llama_sample_top_p_top_k')
            ->willReturn(1);

        $this->ffiMock->method('new')
            ->willReturn(FFI::new('int'));

        $this->ffiMock->method('llama_token_to_str')
            ->willReturn('test');

        $this->ffiMock->method('addr')
            ->willReturn(FFI::new('int'));

        $llamaCPP = new LLamaCPP($this->contextMock, $this->eventDispatcherMock, $this->ffiMock);

        $result = $llamaCPP->generateAll('test', new GenerationParameters(predictLength: 1));

        $this->assertEquals('test', $result);
    }
}
