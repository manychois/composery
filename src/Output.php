<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Output implements OutputInterface
{
    private string $message = '';
    private int $verbosity = self::VERBOSITY_NORMAL;
    private bool $decorated = false;
    private OutputFormatterInterface $formatter;

    public function __construct()
    {
        $this->formatter = new OutputFormatter(true, []);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #region implements OutputInterface

    public function write(string|iterable $messages, bool $newline = false, int $options = 0): void
    {
        $optVerbosity = $options & (
            self::VERBOSITY_QUIET |
            self::VERBOSITY_NORMAL |
            self::VERBOSITY_VERBOSE |
            self::VERBOSITY_VERY_VERBOSE |
            self::VERBOSITY_DEBUG
        );
        if ($optVerbosity > $this->verbosity) {
            return;
        }

        $msg = is_string($messages) ? $messages : implode(', ', $messages);
        if ($newline) {
            $msg .= "\n";
        }
        $this->message .= $msg;
    }

    public function writeln(string|iterable $messages, int $options = 0): void
    {
        $this->write($messages, true, $options);
    }

    public function setVerbosity(int $level): void
    {
        $this->verbosity = $level;
    }

    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    public function isQuiet(): bool
    {
        return $this->verbosity === self::VERBOSITY_QUIET;
    }

    public function isVerbose(): bool
    {
        return $this->verbosity === self::VERBOSITY_VERBOSE;
    }

    public function isVeryVerbose(): bool
    {
        return $this->verbosity === self::VERBOSITY_VERY_VERBOSE;
    }

    public function isDebug(): bool
    {
        return $this->verbosity === self::VERBOSITY_DEBUG;
    }

    public function setDecorated(bool $decorated): void
    {
        $this->decorated = $decorated;
    }

    public function isDecorated(): bool
    {
        return $this->decorated;
    }

    public function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function getFormatter(): OutputFormatterInterface
    {
        return $this->formatter;
    }

    #endregion
}
