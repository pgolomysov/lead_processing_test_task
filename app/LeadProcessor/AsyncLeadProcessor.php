<?php

use LeadGenerator\Lead;

class AsyncLeadProcessor implements LeadProcessorInterface
{
    /**
     * @var int
     */
    private $maxQueueCount;

    /**
     * @var int
     */
    private $sleep;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    const MAX_QUEUE_COUNT_DEFAULT = 10;
    const SLEEP_DEFAULT = 2;

    public function __construct(array $config)
    {
       $this->maxQueueCount = $config['max_queue_count'] ?? self::MAX_QUEUE_COUNT_DEFAULT;
       $this->sleep = $config['sleep'] ?? self::SLEEP_DEFAULT;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function processOne(Lead $lead): void
    {
        sleep($this->sleep);
        $this->log($lead);
    }

    private function log(Lead $lead): void
    {
        $message = sprintf("%s | %s | %s", $lead->id, $lead->categoryName, date('Y-m-d H:i:s'));
        $this->logger->log($message);
    }
}