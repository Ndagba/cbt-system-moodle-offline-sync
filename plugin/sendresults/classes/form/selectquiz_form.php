<?php
namespace local_sendresults\form;

use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class selectquiz_form extends moodleform {
    public function definition() {
        $mform = $this->_form;
        $quizzes = $this->_customdata['quizzes'];

        $mform->addElement('select', 'quizid', get_string('selectquiz', 'local_sendresults'), $quizzes);
        $mform->setType('quizid', PARAM_INT);
        $mform->addRule('quizid', null, 'required');

        $this->add_action_buttons(true, 'Proceed');
    }
}
