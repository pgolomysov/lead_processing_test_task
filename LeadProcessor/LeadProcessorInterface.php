<?php

use LeadGenerator\Lead;

interface LeadProcessorInterface
{
    public function __construct(array $config);
    public function processOne(Lead $lead): void;
    public function setLogger(LoggerInterface $logger): void;
}