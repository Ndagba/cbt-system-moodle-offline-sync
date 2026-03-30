<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__.'/send.php'); // Core sending logic
require_login();

// Get required params
$quizid   = required_param('quizid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Validate course and quiz
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$quiz = $DB->get_record('quiz', ['id' => $quizid], '*', MUST_EXIST);

$cm = get_coursemodule_from_instance('quiz', $quizid, $courseid, false, MUST_EXIST);
$context = context_module::instance($cm->id);
require_capability('local/sendresults:send', $context);

// Prepare results and send
$results_array = local_sendresults_prepare_data_array($courseid, $quizid);
$success = local_sendresults_send($courseid, $quizid, false);

// Detailed feedback
if ($success) {
    $totalusers = count($results_array);

    // Preview of section totals (first few users)
    $preview = '';
    $preview_limit = 3; // number of users to preview
    $count = 0;

    foreach ($results_array as $userresult) {
        $count++;
        $sections = [];
        if (!empty($userresult['sections']) && is_array($userresult['sections'])) {
            foreach ($userresult['sections'] as $sectionname => $score) {
                $sections[] = "$sectionname: $score";
            }
        }
        $sections_str = empty($sections) ? '-' : implode(', ', $sections);
        $preview .= "{$userresult['firstname']} {$userresult['lastname']} => $sections_str\n";

        if ($count >= $preview_limit) {
            break;
        }
    }
    if ($totalusers > $preview_limit) {
        $preview .= "...and " . ($totalusers - $preview_limit) . " more users\n";
    }

    \core\notification::success(
        "✅ CBT result sent successfully via email and central server.<br>
        Total users processed: $totalusers<br>
        <pre>$preview</pre>"
    );
} else {
    \core\notification::error('❌ CBT Result not sent. Check your internet connection or configuration.');
}

// Redirect back to quiz view page after 5 seconds
redirect(new moodle_url('/mod/quiz/view.php', ['id' => $cm->id]), '', 5);
