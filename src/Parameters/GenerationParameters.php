<?php

namespace Kambo\LLamaCpp\Parameters;

final class GenerationParameters
{
    public function __construct(
        private int $predictLength = 128,
        private float $topP = 0.9,
        private float $topK = 40,
        private float $temperature = 0.2,
        private float $repeatPenalty = 1/0.85,
    ) {
    }

    public function getPredictLength(): int
    {
        return $this->predictLength;
    }

    public function getTopP(): float
    {
        return $this->topP;
    }

    public function getTopK(): float
    {
        return $this->topK;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getRepeatPenalty(): float
    {
        return $this->repeatPenalty;
    }
}
