<?php

class FileLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . "\n";
    }
}
