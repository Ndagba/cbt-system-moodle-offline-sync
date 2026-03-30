<?php

$string['pluginname'] = 'Send Results';
$string['sendresults'] = 'Send Results';
$string['sendresults:send'] = 'Send quiz results to central server';

$string['serverurl'] = 'Central server URL';
$string['serverurl_desc'] = 'Enter the secure central server endpoint for receiving result submissions.';

$string['apikey'] = 'API key';
$string['apikey_desc'] = 'Secure API key used to authorize result submissions.';

$string['emailrecipient'] = 'Recipient email address';
$string['emailrecipient_desc'] = 'Enter the email address to send quiz results to (in addition to server endpoint).';

$string['selectquiz'] = 'Select a quiz';

$string['email_subject'] = 'CBT Quiz Results - Course ID: {$a->courseid}, Quiz ID: {$a->quizid}';
$string['email_body'] = 'Attached is the CSV export of the quiz results.';

$string['emailmessage'] = 'Custom email message';
$string['emailmessage_desc'] = 'This message will be sent as the body of the CBT result email. You can use placeholders like {$quizid}, {$courseid}, {$quizname}, and {$coursename}.';

$string['localservername'] = 'Server Name';
$string['servername_desc'] = 'A short identifier for this server (e.g., “Nasarawa_Center” or “BenueHQ”). It will be included in filenames and logs to identify the source.';

