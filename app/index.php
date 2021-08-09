<?php

use LeadGenerator\Generator;
use LeadGenerator\Lead;

//TODO: autoloader
require_once('vendor/autoload.php');
require_once('LeadProcessor/LeadProcessorInterface.php');
require_once('LeadProcessor/AsyncLeadProcessor.php');
require_once('Logger/LoggerInterface.php');
require_once('Logger/ConsoleLogger.php');

$asyncLeadProcessor = new AsyncLeadProcessor(['max_thread_count' => 60, 'sleep' => 2]);
$asyncLeadProcessor->setLogger(new ConsoleLogger());

$generator = new Generator();
$generator->generateLeads(10000, function (Lead $lead) use ($asyncLeadProcessor) {
    $asyncLeadProcessor->processOne($lead);
});