<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Inject "Send Results" into the course navigation drawer and quiz activity menu.
 */

function local_sendresults_extend_navigation_course(navigation_node $nav, stdClass $course, context_course $context) {
    global $PAGE;

    if (!has_capability('local/sendresults:send', $context)) {
        return;
    }

    $url = new moodle_url('/local/sendresults/selectquiz.php', ['courseid' => $course->id]);

    $nav->add(
        get_string('sendresults', 'local_sendresults'),
        $url,
        navigation_node::TYPE_CUSTOM,
        null,
        'sendresults_course', // ✅ Use unique key for node
        new pix_icon('i/email', '')
    );
}


function local_sendresults_extend_settings_navigation(settings_navigation $settingsnav, context $context) {
    global $PAGE, $COURSE, $DB;

    if (!has_capability('local/sendresults:send', $context)) {
        return;
    }

    if ($PAGE->cm && $PAGE->cm->modname === 'quiz') {
        $quizid = $PAGE->cm->instance;

        // ✅ Check if quiz has closed
        $quiz = $DB->get_record('quiz', ['id' => $quizid], '*', IGNORE_MISSING);
        if (!$quiz || ($quiz->timeclose != 0 && $quiz->timeclose > time())) {
            return; // Quiz hasn't closed yet, so don't show the button
        }

        $url = new moodle_url('/local/sendresults/index.php', [
            'courseid' => $COURSE->id,
            'quizid' => $quizid,
        ]);

        $node = $settingsnav->find('modulesettings', navigation_node::TYPE_SETTING);
        if ($node) {
            $node->add(
                get_string('sendresults', 'local_sendresults'),
                $url,
                navigation_node::TYPE_CUSTOM,
                null,
                'sendresults',
                new pix_icon('i/email', '')
            );
        }
    }
}



