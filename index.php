<?php

use LeadGenerator\Generator;
use LeadGenerator\Lead;

require_once('vendor/autoload.php');

$generator = new Generator;

$generator->generateLeads(5, function (Lead $lead) {
    echo sprintf("%s %s\n", $lead->id, $lead->categoryName);
});