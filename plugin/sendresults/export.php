<?php
// File: local/sendresults/export.php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/lib.php');

/**
 * Get all quiz sections in order (Moodle 4.4)
 *
 * @param int $quizid
 * @return array of section objects with id, firstslot, heading
 */
function local_sendresults_get_quiz_sections(int $quizid): array {
    global $DB;
    $sections = $DB->get_records('quiz_sections', ['quizid' => $quizid], 'firstslot ASC');
    return $sections ?: [];
}

/**
 * Compute total score per section for a user (Moodle 4.4 compatible)
 *
 * @param int $quizid
 * @param int $userid
 * @return array sectionname => score
 */
function local_sendresults_get_section_totals(int $quizid, int $userid): array {
    global $DB;

    $sections = local_sendresults_get_quiz_sections($quizid);
    if (!$sections) {
        return [];
    }

    // Get the latest finished attempt
    $attempt = $DB->get_record_sql("
        SELECT *
        FROM {quiz_attempts}
        WHERE quiz = ? AND userid = ? AND state = 'finished'
        ORDER BY attempt DESC
        LIMIT 1
    ", [$quizid, $userid]);

    if (!$attempt) {
        return [];
    }

    // Load user question engine state
    $quba = question_engine::load_questions_usage_by_activity($attempt->uniqueid);

    // Load all quiz slots
    $slots = $DB->get_records('quiz_slots', ['quizid' => $quizid], 'slot ASC');

    $sectiontotals = [];
    $sectionlist = array_values($sections);

    foreach ($sectionlist as $i => $section) {
        $section_name = $section->heading ?: "Section {$section->firstslot}";
        $sectiontotals[$section_name] = 0;

        $startslot = $section->firstslot;
        $endslot = isset($sectionlist[$i + 1]) ? $sectionlist[$i + 1]->firstslot - 1 : max(array_column($slots, 'slot'));

        foreach ($slots as $slot) {
            if ($slot->slot < $startslot || $slot->slot > $endslot) {
                continue;
            }
            try {
                $mark = $quba->get_question_mark($slot->slot);
                if ($mark !== null) {
                    $sectiontotals[$section_name] += $mark;
                }
            } catch (Exception $e) {
                // ignore failed questions
                continue;
            }
        }
    }

    return $sectiontotals;
}

/**
 * Generate CSV file with full quiz results including section totals
 *
 * @param int $courseid
 * @param int $quizid
 * @return string full path to generated CSV
 */
function local_sendresults_generate_csv(int $courseid, int $quizid): string {
    global $DB, $CFG;

    $servername = get_config('local_sendresults', 'localservername') ?: 'unknown';
    $timestamp = date('Ymd_His');
    $filename = "cbt_{$servername}_results_{$timestamp}.csv";

    $csvexporter = new csv_export_writer();
    $csvexporter->set_filename($filename);

    // Get section names
    $sections = local_sendresults_get_quiz_sections($quizid);
    $sectionnames = array_map(fn($s) => $s->heading ?: "Section {$s->firstslot}", $sections);

    // Header row
    $header = ['Last name', 'First name', 'Username', 'ID number', 'Institution', 'State', 'Total Grade'];
    foreach ($sectionnames as $name) {
        $header[] = $name . ' Score';
    }
    $csvexporter->add_data($header);

    // Fetch users
    $users = $DB->get_records_sql("
        SELECT u.id, u.firstname, u.lastname, u.username, u.idnumber, u.institution, u.department AS state
        FROM {user} u
        JOIN {user_enrolments} ue ON ue.userid = u.id
        JOIN {enrol} e ON e.id = ue.enrolid
        WHERE e.courseid = ?
    ", [$courseid]);

    foreach ($users as $user) {
        $g = $DB->get_record('quiz_grades', ['quiz' => $quizid, 'userid' => $user->id]);
        $totalgrade = $g->grade ?? 0;

        $sectionscores = local_sendresults_get_section_totals($quizid, $user->id);

        $row = [
            $user->lastname,
            $user->firstname,
            $user->username,
            $user->idnumber,
            $user->institution,
            $user->state,
            $totalgrade
        ];

        foreach ($sectionnames as $name) {
            $row[] = $sectionscores[$name] ?? 0;
        }

        $csvexporter->add_data($row);
    }

    $tempdir = make_temp_directory('sendresults');
    $filepath = $tempdir . DIRECTORY_SEPARATOR . $filename;

    file_put_contents($filepath, $csvexporter->print_csv_data(true));

    return $filepath;
}

/**
 * Prepare JSON array including section totals
 *
 * @param int $courseid
 * @param int $quizid
 * @return array
 */
function local_sendresults_prepare_data_array(int $courseid, int $quizid): array {
    global $DB;

    $sections = local_sendresults_get_quiz_sections($quizid);
    $sectionnames = array_map(fn($s) => $s->heading ?: "Section {$s->firstslot}", $sections);

    $results = [];

    $users = $DB->get_records_sql("
        SELECT u.id, u.lastname, u.firstname, u.username, u.idnumber, u.institution, u.department AS state
        FROM {user} u
        JOIN {user_enrolments} ue ON ue.userid = u.id
        JOIN {enrol} e ON e.id = ue.enrolid
        WHERE e.courseid = ?
    ", [$courseid]);

    foreach ($users as $user) {
        $g = $DB->get_record('quiz_grades', ['quiz' => $quizid, 'userid' => $user->id]);
        $totalgrade = $g->grade ?? 0;

        $sectionscores = local_sendresults_get_section_totals($quizid, $user->id);

        $results[] = [
            'lastname'    => $user->lastname,
            'firstname'   => $user->firstname,
            'username'    => $user->username,
            'idnumber'    => $user->idnumber,
            'institution' => $user->institution,
            'state'       => $user->state,
            'totalgrade'  => $totalgrade,
            'sections'    => $sectionscores
        ];
    }

    return $results;
}
