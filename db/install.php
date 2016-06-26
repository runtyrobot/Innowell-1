<?php
/**
 * Faktura Block - Faux Data Generation
 * @package block_faktura
 * @copyright none
 * @author Theis Thompson <theisthompson@gmail.com>
 * @license DBID
 */

GLOBAL $DB;

$insert_course = new stdClass();
$insert_course->course_name = "Course " . rand(0,9999); // Defining Course Name Randomly
$insert_course->course_price = rand(1000,10000); // Defining Price Randomly
$DB->insert_record('inno_course', $insert_course, false);
