<?php

class ConsoleLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        echo $message . "\n";
    }
}
