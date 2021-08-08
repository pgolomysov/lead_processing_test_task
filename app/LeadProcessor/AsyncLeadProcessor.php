<?php

use LeadGenerator\Lead;
use parallel\Channel;
use parallel\Runtime;

class AsyncLeadProcessor implements LeadProcessorInterface
{
    private int $maxThreadsCount;

    private int $threadsCount;

    private array $threads;

    private int $sleep;

    private LoggerInterface $logger;

    const MAX_THREAD_COUNT_DEFAULT = 10;
    const SLEEP_DEFAULT = 2;

    public function __construct(array $config)
    {
       $this->maxThreadsCount = $config['max_thread_count'] ?? self::MAX_THREAD_COUNT_DEFAULT;
       $this->sleep = $config['sleep'] ?? self::SLEEP_DEFAULT;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function processOne(Lead $lead): void
    {
        $callable = function(array $lead, int $sleep) {
            sleep($sleep);
            $message = sprintf("%s | %s | %s \n", $lead['id'], $lead['categoryName'], date('Y-m-d H:i:s'));
            $file = fopen('logs/log.txt', 'a+');
            fwrite($file, $message);
            fclose($file);
        };

        while (true) {
            if ($this->threadsCount < $this->maxThreadsCount) {
                $runtime = new Runtime();

                $future = $runtime->run($callable, [(array)$lead, $this->sleep]);
                $this->threadsCount++;
                echo '[debug]' . ' Сейчас тредов: '. $this->threadsCount . "\n";

                $this->threads[] = $future;
                break;
            } else {
                echo '[debug]' . ' Тредов слишком много, чищу. Сейчас их ' . $this->threadsCount . "\n";
                $this->cleanThreads();
                echo '[debug]' . ' Теперь тредов: '. $this->threadsCount . "\n";
            }
            usleep(300000);
        }
    }

    private function cleanThreads()
    {
        foreach ($this->threads as $key => $future) {
            if ($future->done()) {
                unset($this->threads[$key]);
                $this->threadsCount--;
            }
        }
    }
}