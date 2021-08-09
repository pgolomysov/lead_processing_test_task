<?php

use LeadGenerator\Lead;
use parallel\Channel;
use parallel\Runtime;

class AsyncLeadProcessor implements LeadProcessorInterface
{
    private LoggerInterface $logger;

    private int $maxThreadsCount;
    private int $threadsCount = 0;
    private array $threads;
    private int $sleep;

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
        while (true) {
            if ($this->threadsCount < $this->maxThreadsCount) {
                $this->runOne($lead);
                break;
            } else {
                $this->logger->log('Тредов слишком много, чищу. Сейчас их ' . $this->threadsCount);
                $this->cleanDoneThreads();
                $this->logger->log('Теперь тредов: '. $this->threadsCount);
            }
            usleep(300000);
        }
    }

    private function runOne(Lead $lead): void
    {
        $runtime = new Runtime();
        $future = $runtime->run($this->getCallable(), [(array)$lead, $this->sleep]);
        $this->threads[] = $future;
        $this->threadsCount++;

        $this->logger->log('Сейчас тредов: '. $this->threadsCount);
    }

    private function getCallable(): callable
    {
        return function(array $lead, int $sleep) {
            sleep($sleep);
            $message = sprintf("%s | %s | %s \n", $lead['id'], $lead['categoryName'], date('Y-m-d H:i:s'));
            $file = fopen('logs/log.txt', 'a+');
            fwrite($file, $message);
            fclose($file);
        };
    }

    private function cleanDoneThreads(): void
    {
        foreach ($this->threads as $key => $future) {
            if ($future->done()) {
                unset($this->threads[$key]);
                $this->threadsCount--;
            }
        }
    }
}