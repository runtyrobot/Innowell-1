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
    // For the sake of this, we'll copy it to our own table 'inno_user'
    if ($DB->count_records('inno_course') < 1) {

        // Generate Course
        generate_course();

        // Generate User
        generate_user();

        // Generate Signups

    }

}

/**
 * Generate Course for Database Fill
 * @param int $n
 */
function generate_course($n = 10) {
    global $DB;
    for ($i = 0; $i < $n; $i++) {

        $insert_course = new stdClass();
        $insert_course->course_name = "Course " . $i; // Rely on increment integer to avoid name collision
        $insert_course->course_price = rand(1000,10000); // Defining Price Randomly
        $DB->insert_record('inno_course', $insert_course, false);

    }
}

/**
 * Generate User from Existing Data
 */
function generate_user() {
    global $DB, $USER;

    $user                   = new stdClass();
    $user->id               = $USER->id;
    $user->user_firstname   = $USER->firstname;
    $user->user_surname     = $USER->lastname;
    $user->user_email       = $USER->email;
    $user->user_moodle_id   = $USER->id;

    $DB->insert_record('inno_user', $user, false);

}