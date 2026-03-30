<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\mod_quiz\event\attempt_submitted',
        'callback'    => 'local_sendresults_observer::auto_send_results_on_close',
        'includefile' => '/local/sendresults/classes/observer.php',
        'internal'    => false,
        'priority'    => 1000,
    ],
];
