<?php

/**
 * Faktura Block
 * @package block_faktura
 * @copyright none
 * @author Theis Thompson <theisthompson@gmail.com>
 * @license DBID
 */

defined('MOODLE_INTERNAL') || die();

/*-------------------------------------------------------------
    Faktura Block Class
-------------------------------------------------------------*/

class block_faktura extends block_base {
    // Init Block
    public function init() {
        $this->title = get_string('faktura_block_title', 'block_faktura');
    }


    // Get Content
    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT, $PAGE;
        if ($this->content !== null) {
            return $this->content;
        }

        // Could be done with a single SQL query - but let's try it this way.
        $signups = $DB->get_records('user_signup', array('key_user' => $USER->id));

        // Initiate content
        $this->content                  =  new stdClass;
        $this->content->text            = ''; // Avoid Notices

        foreach ($signups as $s) {
            // Get User Payments / Bills
            $payment = $DB->get_records('user_payment', array('key_signup' => $s->id));
            // Get Course Name
            $course_title = $DB->get_field('inno_course', 'course_name', array('id' => $s->key_course));
            $this->content->text        .= html_writer::start_tag('h3');
            $this->content->text        .= $course_title;
            $this->content->text        .= html_writer::end_tag('h3');

            $this->content->text        .= html_writer::start_tag('ul');
            foreach ($payment as $p) {
                $this->content->text    .= html_writer::start_tag('li');
                $this->content->text    .= date('d. M Y', $p->pay_date);
                $this->content->text    .= $p->pay_paid;
                $this->content->text    .= html_writer::end_tag('li');
            }
            $this->content->text        .= html_writer::end_tag('ul');
        }

        $this->content->footer  = '';

        return $this->content;
    }


    // Allow multiple instances
    public function instance_allow_multiple() {
        return false; // Doesn't matter - but let's show that this exists.
    }

}