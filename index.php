<?php

use LeadGenerator\Generator;
use LeadGenerator\Lead;

require_once('vendor/autoload.php');
require_once('LeadProcessor/LeadProcessorInterface.php');
require_once('LeadProcessor/AsyncLeadProcessor.php');
require_once('LeadProcessor/TimerProxyLeadProcessor.php');
require_once('Logger/LoggerInterface.php');
require_once('Logger/FileLogger.php');

$asyncLeadProcessor = new AsyncLeadProcessor(['max_queue_count' => 30, 'sleep' => 2]);
$asyncLeadProcessor->setLogger(new FileLogger());

$timerProxyLeadProcessor = new TimerProxyLeadProcessor(['original_object' => $asyncLeadProcessor]);

$generator = new Generator;
$generator->generateLeads(5, function (Lead $lead) use ($timerProxyLeadProcessor) {
    $timerProxyLeadProcessor->processOne($lead);
});