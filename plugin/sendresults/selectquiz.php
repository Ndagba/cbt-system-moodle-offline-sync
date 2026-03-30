<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
$course = get_course($courseid);
$context = context_course::instance($courseid);

require_capability('local/sendresults:send', $context);

$PAGE->set_url(new moodle_url('/local/sendresults/selectquiz.php', ['courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('sendresults', 'local_sendresults'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('sendresults', 'local_sendresults'), 2);

// Fetch quizzes
$quizzes = $DB->get_records('quiz', ['course' => $courseid]);

if (empty($quizzes)) {
    echo $OUTPUT->notification('⚠️ No quizzes found in this course.', 'warning');
    echo $OUTPUT->footer();
    exit;
}

// UI Form
echo html_writer::start_div('container mt-4');
echo html_writer::start_div('card shadow-sm p-4');

echo html_writer::tag('h4', 'Select Quiz to Send Results', ['class' => 'mb-4']);

$formurl = new moodle_url('/local/sendresults/index.php');

echo html_writer::start_tag('form', [
    'method' => 'get',
    'action' => $formurl->out(false),
    'class' => 'form-inline'
]);

echo html_writer::empty_tag('input', [
    'type' => 'hidden',
    'name' => 'courseid',
    'value' => $courseid
]);

// Quiz dropdown
$select = html_writer::start_div('form-group mb-3');
$select .= html_writer::label('Select Quiz', 'quizid', false, ['class' => 'form-label']);
$select .= html_writer::start_tag('select', [
    'name' => 'quizid',
    'id' => 'quizid',
    'class' => 'form-control w-100'
]);

foreach ($quizzes as $quiz) {
    $select .= html_writer::tag('option', format_string($quiz->name), ['value' => $quiz->id]);
}

$select .= html_writer::end_tag('select');
$select .= html_writer::end_div();
echo $select;

// Submit button
echo html_writer::empty_tag('br');
echo html_writer::tag('button', 'Continue', [
    'type' => 'submit',
    'class' => 'btn btn-primary mt-2'
]);

echo html_writer::end_tag('form');
echo html_writer::end_div(); // card
echo html_writer::end_div(); // container

echo $OUTPUT->footer();
