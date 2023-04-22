<?php

namespace Kambo\Tests\LLamaCPP\Parameters;

use Kambo\LLamaCPP\Parameters\GenerationParameters;
use PHPUnit\Framework\TestCase;

class GenerationParametersTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $predictLength = 128;
        $topP          = 0.9;
        $topK          = 40;
        $temperature   = 0.2;
        $repeatPenalty = 1 / 0.85;

        $generationParameters = new GenerationParameters(
            $predictLength,
            $topP,
            $topK,
            $temperature,
            $repeatPenalty
        );

        $this->assertSame($predictLength, $generationParameters->getPredictLength());
        $this->assertEqualsWithDelta($topP, $generationParameters->getTopP(), 0.1);
        $this->assertEqualsWithDelta($topK, $generationParameters->getTopK(), 0.1);
        $this->assertEqualsWithDelta($temperature, $generationParameters->getTemperature(), 0.1);
        $this->assertEqualsWithDelta($repeatPenalty, $generationParameters->getRepeatPenalty(), 0.1);
    }

    public function testConstructorWithDefaultValues()
    {
        $generationParameters = new GenerationParameters();

        $this->assertSame(128, $generationParameters->getPredictLength());
        $this->assertEqualsWithDelta(0.9, $generationParameters->getTopP(), 0.1);
        $this->assertEqualsWithDelta(40, $generationParameters->getTopK(), 0.1);
        $this->assertEqualsWithDelta(0.2, $generationParameters->getTemperature(), 0.1);
        $this->assertEqualsWithDelta(1 / 0.85, $generationParameters->getRepeatPenalty(), 0.1);
    }
}
