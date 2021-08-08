<?php

class FileLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        $file = fopen('logs/log.txt', 'a+');
        fwrite($file, $message . "\n");
        fclose($file);
    }
}
