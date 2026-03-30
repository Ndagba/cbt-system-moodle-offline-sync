<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_sendresults', get_string('pluginname', 'local_sendresults'));

    // Server name
    $settings->add(new admin_setting_configtext(
    'local_sendresults/localservername',
    get_string('localservername', 'local_sendresults'),
    get_string('servername_desc', 'local_sendresults'),
    '',
    PARAM_TEXT
    ));

    // Central server URL
    $settings->add(new admin_setting_configtext(
        'local_sendresults/serverurl',
        get_string('serverurl', 'local_sendresults'),
        get_string('serverurl_desc', 'local_sendresults'),
        '',
        PARAM_URL
    ));

    // API key
    $settings->add(new admin_setting_configtext(
        'local_sendresults/apikey',
        get_string('apikey', 'local_sendresults'),
        get_string('apikey_desc', 'local_sendresults'),
        '',
        PARAM_ALPHANUMEXT
    ));

    // email address to send results to
    $settings->add(new admin_setting_configtext(
        'local_sendresults/emailrecipient', // setting name
        get_string('emailrecipient', 'local_sendresults'), // visible name
        get_string('emailrecipient_desc', 'local_sendresults'), // description
        'ndagba4me@gmail.com', // default value
        PARAM_EMAIL // validation type
    ));

    $settings->add(new admin_setting_configtextarea(
    'local_sendresults/emailmessage',
    get_string('emailmessage', 'local_sendresults'),
    get_string('emailmessage_desc', 'local_sendresults'),
    'The CBT Exams Result for exam ID {$quizid} in course {$courseid} is attached.'
    ));


    $ADMIN->add('localplugins', $settings);
}


