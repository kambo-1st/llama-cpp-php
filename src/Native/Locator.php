<?php

namespace Kambo\LLamaCPP\Native;

use Kambo\LLamaCPP\Exception\MissingLibraryException;
use Kambo\LLamaCPP\Exception\NotImplementedException;
use Kambo\LLamaCPPLinuxLib\Info;

use function class_exists;

use const PHP_OS_FAMILY;

/**
 * Quick and naive library locator
 */
final class Locator implements LocateLib
{
    public function getLibraryPath(): string
    {
        if (PHP_OS_FAMILY !== 'Linux' || PHP_OS_FAMILY !== 'Unknown') {
            if (class_exists(Info::class)) {
                $info = new Info();
                return $info->getPath();
            }

            throw new MissingLibraryException('Cannot find LLamaCPP library.');
        }

        throw new NotImplementedException('At this moment only Linux is supported. Platform: ' . PHP_OS_FAMILY);
    }
}
