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

    /**
     * Init Block
     */
    public function init() {
        $this->title = get_string('faktura_block_title', 'block_faktura');
    }


    /**
     * Block Content
     * @return stdClass
     */
    public function get_content() {
        global $USER, $DB;

        // Avoid Code Runs Forever if there's no content.
        if ($this->content !== null) {
            return $this->content;
        }

        // Initiate content
        $this->content                  =  new stdClass;
        $this->content->text            = ''; // Avoid Notices

        // Added Custom Functions for readability and Access
        if (is_siteadmin($USER->id)) {
            $this->get_bill_list_admin();
        } else {
            $this->get_bill_list($USER->id);
        }

        $this->content->footer  = '';

        return $this->content;
    }

    /**
     * Get bill list for USER
     * @param int $user
     */
    private function get_bill_list($user = 0) {
        global $DB, $USER;

        // The data request itself
        // Could be done with a single SQL query - but let's try it this way.
        $signups = $DB->get_records('user_signup', array('key_user' => $user));

        foreach ($signups as $s) {
            // Get User Payments / Bills
            $payment = $DB->get_records('user_payment', array('key_signup' => $s->id));

            // Get Course Name
            $course_title = $DB->get_field('inno_course', 'course_name', array('id' => $s->key_course));

            // User HTML writer to add course header
            $this->content->text        .= html_writer::start_tag('h3');
            $this->content->text        .= $course_title;
            $this->content->text        .= html_writer::end_tag('h3');

            // Start Bill List
            $this->content->text        .= html_writer::start_tag('ul');

            // Add Each Bill to List
            foreach ($payment as $p) {
                $this->content->text    .= html_writer::start_tag('li');

                // Create a link if the bill has been paid
                // Else return header
                if ($p->pay_paid) {
                    $url                 = new moodle_url('/blocks/faktura/viewpdf.php', array('pay_id' => $p->id, 'pay_fname' => $USER->firstname, 'pay_lname' => $USER->lastname));
                    $this->content->text.= html_writer::link($url, "Faktura " . $p->id . " ",  array('target' => '_blank'));
                } else {
                    $this->content->text    .= "Faktura " . $p->id . " ";
                }

                // Add pay date and end tag
                $this->content->text    .= "(" . date('d. M Y', $p->pay_date) . ")";
                $this->content->text    .= html_writer::end_tag('li');
            }

            // End Bill List
            $this->content->text        .= html_writer::end_tag('ul');
        }
    }

    /**
     * Get Full Bill List
     * Sorted by Course -> User
     */
    private function get_bill_list_admin() {
        global $DB;

        // Get User Payments / Bills
        $courses = $DB->get_records('inno_course');

        foreach ($courses as $c) {
            // Add Header to Course
            $this->content->text        .= html_writer::start_tag('h3');
            $this->content->text        .= $c->course_name;
            $this->content->text        .= html_writer::end_tag('h3');

            $signup = $DB->get_records('user_signup', array('key_course' => $c->id));

            foreach ($signup as $s) {
                $user = $DB->get_records('user', array('id' => $s->key_user));

                foreach ($user as $u) {
                    $this->content->text    .= html_writer::start_tag('h5');
                    $this->content->text    .= $u->id . " - " . $u->firstname . " " . $u->lastname;
                    $this->content->text    .= html_writer::end_tag('h5');

                    $payment = $DB->get_records('user_payment', array('key_signup' => $s->id));

                    $this->content->text    .= html_writer::start_tag('ul');
                    foreach ($payment as $p) {
                        $this->content->text    .= html_writer::start_tag('li');

                        // Create a link if the bill has been paid
                        // Else return header
                        if ($p->pay_paid) {
                            $url                 = new moodle_url('/blocks/faktura/viewpdf.php', array('pay_id' => $p->id, 'pay_fname' => $u->firstname, 'pay_lname' => $u->lastname));
                            $this->content->text.= html_writer::link($url, "Faktura " . $p->id . " ",  array('target' => '_blank'));
                        } else {
                            $this->content->text    .= "Faktura " . $p->id . " ";
                        }

                        // Add pay date and end tag
                        $this->content->text    .= "(" . date('d. M Y', $p->pay_date) . ")";
                        $this->content->text    .= html_writer::end_tag('li');
                    }
                    $this->content->text    .= html_writer::end_tag('ul');
                }
            }
        }
    }

}