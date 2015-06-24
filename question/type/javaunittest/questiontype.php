<?php
/**
 * The question type class for this question type.
 *
 * @package    qtype
 * @subpackage javaunittest
 * @author     Gergely Bertalan, bertalangeri@freemail.hu
 * @reference  sojunit 2008, Süreç Özcan, suerec@darkjade.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined ( 'MOODLE_INTERNAL' ) || die ();

require_once ($CFG->libdir . '/questionlib.php');

define ( 'FEEDBACK_NOTHING', -1 );
define ( 'FEEDBACK_ONLY_TIMES', 0 );
define ( 'FEEDBACK_TIMES_COUNT_OF_TESTS', 1 );
define ( 'FEEDBACK_ALL_EXCEPT_STACKTRACE', 2 );

/**
 * The javaunittest question type.
 *
 * @copyright 2005 Mark Nielsen
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_javaunittest extends question_type {
    /**
     * Whether this question type can perform a frequency analysis of student responses.
     * If this method returns true, you must implement the get_possible_responses method, and the question_definition
     * class must implement the classify_response method.
     *
     * @return bool whether this report can analyse all the student responses for things like the quiz statistics
     *         report.
     */
    public function can_analyse_responses() {
        return false;
    }
    
    /**
     * If your question type has a table that extends the question table, and you want the base class to automatically
     * save, backup and restore the extra fields, override this method to return an array wherer the first element is
     * the table name, and the subsequent entries are the column names (apart from id and questionid).
     *
     * @return mixed array as above, or null to tell the base class to do nothing.
     */
    public function extra_question_fields() {
        return array (
                'qtype_javaunittest_options',
                'responsefieldlines',
                'givencode',
                'testclassname',
                'junitcode',
                'feedbacklevel' 
        );
    }
    
    /**
     * Whether or not to break down question stats and response analysis, for a question defined by $questiondata.
     *
     * @param object $questiondata The full question definition data.
     * @return bool
     */
    public function break_down_stats_and_response_analysis_by_variant($questiondata) {
        return false;
    }
    
    /**
     *
     * @return array the different response formats that the question type supports. internal name => human-readable
     *         name.
     */
    public function response_formats() {
        return array (
                'plain' => get_string ( 'formatplain', 'qtype_javaunittest' ) 
        );
    }
    
    /**
     *
     * @return array the choices that should be offered for the input box size.
     */
    public function response_sizes() {
        $choices = array ();
        for ( $lines = 5; $lines <= 40; $lines += 5 ) {
            $choices[$lines] = get_string ( 'nlines', 'qtype_javaunittest', $lines );
        }
        return $choices;
    }
    
    /**
     *
     * @return array of feedback levels for question edit page
     */
    public function feedback_levels() {
        $levels = array (
                FEEDBACK_NOTHING => get_string ( 'feedback_nothing', 'qtype_javaunittest' ),
                FEEDBACK_ONLY_TIMES => get_string ( 'feedback_only_times', 'qtype_javaunittest' ),
                FEEDBACK_TIMES_COUNT_OF_TESTS => get_string ( 'feedback_times_count_of_tests', 'qtype_javaunittest' ),
                FEEDBACK_ALL_EXCEPT_STACKTRACE => get_string ( 'feedback_all_except_stacktrace', 'qtype_javaunittest' ) 
        );
        
        return $levels;
    }
    public function move_files($questionid, $oldcontextid, $newcontextid) {
        parent::move_files ( $questionid, $oldcontextid, $newcontextid );
        $fs = get_file_storage ();
        $fs->move_area_files_to_new_context ( $oldcontextid, $newcontextid, 'qtype_javaunittest', 'graderinfo', 
                $questionid );
    }
    protected function delete_files($questionid, $contextid) {
        parent::delete_files ( $questionid, $contextid );
        $fs = get_file_storage ();
        $fs->delete_area_files ( $contextid, 'qtype_javaunittest', 'graderinfo', $questionid );
    }
}