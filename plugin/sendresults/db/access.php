<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/sendresults:send' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'manager' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_PREVENT,     
        ],
    ],
];

return $capabilities;
