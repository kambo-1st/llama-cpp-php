<?php

namespace Kambo\LLamaCPP\Tests\Parameters;

use Kambo\LLamaCPP\Parameters\ModelParameters;
use PHPUnit\Framework\TestCase;

class ModelParametersTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $modelPath = 'path/to/model';
        $nCtx = 1024;
        $nParts = -1;
        $seed = 0;
        $f16KV = false;
        $logitsAll = false;
        $vocabOnly = false;
        $useMlock = false;
        $embedding = false;

        $modelParameters = new ModelParameters(
            $modelPath,
            $nCtx,
            $nParts,
            $seed,
            $f16KV,
            $logitsAll,
            $vocabOnly,
            $useMlock,
            $embedding
        );

        $this->assertEquals($modelPath, $modelParameters->getModelPath());
        $this->assertEquals($nCtx, $modelParameters->getNCtx());
        $this->assertEquals($nParts, $modelParameters->getNParts());
        $this->assertEquals($seed, $modelParameters->getSeed());
        $this->assertEquals($f16KV, $modelParameters->isF16KV());
        $this->assertEquals($logitsAll, $modelParameters->isLogitsAll());
        $this->assertEquals($vocabOnly, $modelParameters->isVocabOnly());
        $this->assertEquals($useMlock, $modelParameters->isUseMlock());
        $this->assertEquals($embedding, $modelParameters->isEmbedding());
    }
}
