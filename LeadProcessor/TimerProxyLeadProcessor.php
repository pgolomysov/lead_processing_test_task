<?php

use LeadGenerator\Lead;

class TimerProxyLeadProcessor implements LeadProcessorInterface
{
    /**
     * @var LeadProcessorInterface $originalObject
     */
    private $originalObject;

    private $startTime;

    public function __construct(array $config)
    {
        $this->startTime = time();

        if (!isset($config['original_object'])) {
            //TODO: make my own throwable exception
            throw new Exception('No original object was set');
        }

        $this->originalObject = $config['original_object'];
    }

    public function setLogger(LoggerInterface $logger): void {}

    public function processOne(Lead $lead): void
    {
        $this->originalObject->processOne($lead);
    }
}