<?php
defined('MOODLE_INTERNAL') || die();

class local_sendresults_observer {
    public static function auto_send_results_on_close(\mod_quiz\event\attempt_submitted $event) {
        global $DB;

        $quiz = $DB->get_record('quiz', ['id' => $event->other['quizid']]);
        $cm = get_coursemodule_from_instance('quiz', $quiz->id);
        $context = context_module::instance($cm->id);

        // Check if quiz is closed
        if ($quiz->timeclose && time() >= $quiz->timeclose) {
            // Only send once per quiz (prevent duplicates)
            if (!$DB->record_exists('local_sendresults_log', ['quizid' => $quiz->id])) {
                require_once(__DIR__.'/../lib/send.php');
                local_sendresults_send($quiz->course, $quiz->id, true);
            }
        }
    }
}
