<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Exception;
use PHPUnit\Framework\TestCase;

abstract class AbstractCommandTestCase extends TestCase
{
    private string $prevCwd;
    protected string $cwd;

    /**
     * @param array<string> $lines
     */
    protected static function assertOutputLines(array $lines, string $part): void
    {
        foreach ($lines as $line) {
            if (\str_contains($line, $part)) {
                static::assertTrue(true);
                return;
            }
        }
        static::fail(\sprintf(
            "Expected to find %s in output lines:\n%s\n=== End of output ===\n",
            $part,
            \implode("\n", $lines)
        ));
    }

    /**
     * @param array<string> $lines
     */
    protected static function assertOutputLineMissing(array $lines, string $part): void
    {
        foreach ($lines as $line) {
            static::assertFalse(\str_contains($line, $part), "Expected $part to be missing but found");
        }
    }

    protected function setUp(): void
    {
        $this->prevCwd = \getcwd();
        $this->cwd = __DIR__ . '/cwd';
        $success = \chdir($this->cwd);
        if (!$success) {
            throw new Exception("Failed to change directory to {$this->cwd}");
        }
        $shouldNotPresent = ['composer.json', 'composer.lock', 'src', 'vendor'];
        foreach ($shouldNotPresent as $path) {
            if (\file_exists($path)) {
                throw new Exception("$path already exists");
            }
        }
    }

    protected function tearDown(): void
    {
        $shouldNotPresent = ['composer.json', 'composer.lock', 'src', 'vendor'];
        foreach ($shouldNotPresent as $path) {
            $success = $this->rmr("{$this->cwd}/$path");
            if (!$success && \file_exists($path)) {
                throw new Exception("Failed to delete {$this->cwd}/$path");
            }
        }
        $success = \chdir($this->prevCwd);
        if (!$success) {
            throw new Exception("Failed to change directory to {$this->prevCwd}");
        }
    }

    private function rmr(string $path): bool
    {
        if (!\file_exists($path)) {
            return true;
        }
        if (\is_dir($path)) {
            $hasFailedCase = false;
            foreach (\scandir($path) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $fullPath = "$path/$file";
                if (\is_dir($fullPath)) {
                    $hasFailedCase |= !$this->rmr($fullPath);
                } else {
                    $hasFailedCase |= !\unlink($fullPath);
                }
            }

            return \rmdir($path) && !$hasFailedCase;
        } else {
            return \unlink($path);
        }
    }
}
