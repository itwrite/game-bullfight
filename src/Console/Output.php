<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/26
 * Time: 10:48
 */

namespace Jasmine\Game\Bullfight\Console;


class Output
{
    const OUTPUT_NORMAL = 1;
    const OUTPUT_RAW = 2;
    const OUTPUT_PLAIN = 4;
    
    protected $stream = null;
    
    public function __construct()
    {
        $stream = $this->openOutputStream();
        if (!\is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new InvalidArgumentException('The StreamOutput class needs a stream as its first argument.');
        }

        $this->stream = $stream;
        
        
    }

    /**
     * Returns true if current environment supports writing console output to
     * STDOUT.
     *
     * @return bool
     */
    protected function hasStdoutSupport()
    {
        return false === $this->isRunningOS400();
    }

    /**
     * Returns true if current environment supports writing console output to
     * STDERR.
     *
     * @return bool
     */
    protected function hasStderrSupport()
    {
        return false === $this->isRunningOS400();
    }

    /**
     * Checks if current executing environment is IBM iSeries (OS400), which
     * doesn't properly convert character-encodings between ASCII to EBCDIC.
     *
     * @return bool
     */
    private function isRunningOS400()
    {
        $checks = array(
            \function_exists('php_uname') ? php_uname('s') : '',
            getenv('OSTYPE'),
            PHP_OS,
        );

        return false !== stripos(implode(';', $checks), 'OS400');
    }

    /**
     * @return resource
     */
    private function openOutputStream()
    {
        $outputStream = $this->hasStdoutSupport() ? 'php://stdout' : 'php://output';

        return @fopen($outputStream, 'w') ?: fopen('php://output', 'w');
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        if ($newline) {
            $message .= PHP_EOL;
        }

        if (false === @fwrite($this->stream, $message)) {
            // should never happen
            throw new RuntimeException('Unable to write output.');
        }

        fflush($this->stream);
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages)
    {
        $this->write($messages, true);
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false)
    {
        $messages = (array) $messages;
        
        foreach ($messages as $message) {
            $this->doWrite($message, $newline);
        }
    }
}