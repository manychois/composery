<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Output implements OutputInterface
{
    public int $exitCode = 0;
    /**
     * @var array<string>
     */
    private array $lines = [];
    private string $currentLine = '';
    private int $verbosity = self::VERBOSITY_NORMAL;
    private bool $decorated = false;
    /**
     * Formatter is not used in this class, but it is required by OutputInterface.
     */
    private OutputFormatterInterface $formatter;

    public function __construct()
    {
        $this->formatter = new OutputFormatter(false, []);
    }

    /**
     * @return array<string>
     */
    public function getLines(): array
    {
        $lines = $this->lines;
        if ($this->currentLine !== '') {
            $lines[] = $this->currentLine;
        }
        return $lines;
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

        if (is_string($messages)) {
            $this->currentLine .= $messages;
        } else {
            foreach ($messages as $message) {
                $this->currentLine .= $message;
            }
        }
        if ($newline) {
            $this->currentLine .= "\n";
        }

        $split = explode("\n", $this->currentLine);
        $n = \count($split);
        if ($n > 1) {
            for ($i = 0; $i < $n - 1; $i++) {
                $this->lines[] = $split[$i];
            }
            $this->currentLine = $split[$n - 1];
        }
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
