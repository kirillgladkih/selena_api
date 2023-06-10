<?php

namespace Selena\Logger;

class FileLogger implements LoggerInterface
{

    /**
     * @var string
     */
    protected string $log_directory;

    /**
     * @param string $log_directory
     */
    public function __construct(string $log_directory)
    {
        $this->log_directory = $log_directory;
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log("emergency", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log("alert", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log("critical", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->log("error", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log("warning", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->log("notice", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->log("info", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log("debug", $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, string $message, array $context = []): void
    {
        $directory = $this->log_directory . "/" . $level;

        if(!is_dir($directory)){

            mkdir($directory, 0777, true);

        }

        $file = $directory . "/" .  date("Y-m-d") . ".txt";

        file_put_contents($file, PHP_EOL, FILE_APPEND | LOCK_EX);

        file_put_contents($file, $message, FILE_APPEND | LOCK_EX);

        file_put_contents($file, PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}