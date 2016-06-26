<?php
/**
 * Faktura Block - Faux Data Generation
 * @package block_faktura
 * @copyright none
 * @author Theis Thompson <theisthompson@gmail.com>
 * @license DBID
 */

function  xmldb_block_faktura_install() {
    global $DB;

    // Generate courses if none exist
    // If none exist in 'inno_course' - we assume no relevant data exists at all.
    // Users already exist in Moodle
    if ($DB->count_records('inno_course') < 1) {

        // Generate Course
        generate_course();

        // Generate Signups
        generate_signups();

        // Generate Payments
        generate_payment();

    }

}


/**
 * Generate Course for Database Fill
 * @param int $n - Number of courses added
 */
function generate_course($n = 10) {
    global $DB;
    for ($i = 0; $i < $n; $i++) {

        $insert_course                  = new stdClass();
        $insert_course->course_name     = "Course " . $i; // Rely on increment integer to avoid name collision
        $insert_course->course_price    = rand(1000,10000); // Defining Price Randomly

        $DB->insert_record('inno_course', $insert_course, false);

    }
}


/**
 * Generate Signups for the user currently logged in
 * @param int $n
 */
function generate_signups($n = 3) {
    GLOBAL $DB, $USER;

    for ($i = 0; $i < $n; $i++) {

        $signup             = new stdClass();
        $signup->pay_terms  = $i+1; // Demonstrates multiple cases
        $signup->key_course = $i + 1; // ID 0 isn't possible - so let's get the next one.
        $signup->key_user   = $USER->id;

        $DB->insert_record('user_signup', $signup, false);
    }

}

/**
 * Generate Payment
 * - Generates payments matching the signups for the user who's currently logged in
 */
function generate_payment() {
    GLOBAL $DB;

    // Need arbitrary Unix Timestamp - Let's take today.
    $date = new DateTime();

    // Need Signups to check how many payments we split over and which course
    $terms = $DB->get_records('user_signup');

    foreach ($terms as $term) {
        $pay = new stdClass();
        if ($term->pay_terms > 0) {
            for ($i = 0; $i < $term->pay_terms; $i++) {

                $pay->pay_method    = 1;
                $date->modify('+1 month'); // Add 1 Month to last date
                $pay->pay_date      = $date->getTimestamp(); // Unix Timestamp
                $pay->pay_paid      = 1; //

                // Let's set the last payment to not paid - just as showcase.
                if ($i == $term->pay_terms - 1) {
                    $pay->pay_paid  = 0;
                }

                $pay->key_signup    = $term->id;

                $DB->insert_record('user_payment', $pay, false);
            }
        }
    }

}