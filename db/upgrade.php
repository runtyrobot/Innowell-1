<?php
/**
 * Faktura Block Database Upgrade
 * - Because we can.
 * @package block_faktura
 * @copyright none
 * @author Theis Thompson <theisthompson@gmail.com>
 * @license DBID
 */

/*------------------------------------------------------
    Quickly define newest version
-------------------------------------------------------*/

$newversion = "2016062216";

/*------------------------------------------------------
    Update tables respectively
    - inno_course (top level)
    - inno_user
    - user_payment
    - user_signup
-------------------------------------------------------*/


if ($oldversion < $newversion) {

    /*------------------------------------------------------
        Inno Course (inno_course)
    -------------------------------------------------------*/
    $table = new xmldb_table('inno_course');

    // Adding fields to table inno_course.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('course_name', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, null);
    $table->add_field('course_price', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);

    // Adding keys to table inno_course.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Conditionally launch create table for inno_course.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Faktura savepoint reached.
    upgrade_block_savepoint(true, $newversion, 'faktura');


    /*------------------------------------------------------
        Inno User (inno_user)
    -------------------------------------------------------*/
    $table = new xmldb_table('inno_user');

    // Adding fields to table inno_user.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('user_firstname', XMLDB_TYPE_CHAR, '16', null, XMLDB_NOTNULL, null, null);
    $table->add_field('user_surname', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
    $table->add_field('user_email', XMLDB_TYPE_CHAR, '128', null, XMLDB_NOTNULL, null, null);

    // Adding keys to table inno_user.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Conditionally launch create table for inno_user.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Faktura savepoint reached.
    upgrade_block_savepoint(true, $version, 'faktura');


    /*------------------------------------------------------
        User Signup (user_signup)
    -------------------------------------------------------*/
    $table = new xmldb_table('user_signup');

    // Adding fields to table user_signup.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('pay_terms', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
    $table->add_field('key_course', XMLDB_TYPE_INTEGER, '5', null, XMLDB_NOTNULL, null, null);
    $table->add_field('key_user', XMLDB_TYPE_INTEGER, '5', null, XMLDB_NOTNULL, null, null);

    // Adding keys to table user_signup.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_key('ref_course', XMLDB_KEY_FOREIGN, array('key_course'), 'inno_course', array('id'));
    $table->add_key('ref_user', XMLDB_KEY_FOREIGN, array('key_user'), 'inno_user', array('id'));

    // Conditionally launch create table for user_signup.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Faktura savepoint reached.
    upgrade_block_savepoint(true, $newversion, 'faktura');


    /*------------------------------------------------------
        User Payment (user_payment)
    -------------------------------------------------------*/
    $table = new xmldb_table('user_payment');

    // Adding fields to table user_payment.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('pay_date', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
    $table->add_field('pay_method', XMLDB_TYPE_BINARY, null, null, XMLDB_NOTNULL, null, null);
    $table->add_field('pay_paid', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('key_signup', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);

    // Adding keys to table user_payment.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_key('ref_signup', XMLDB_KEY_FOREIGN, array('key_signup'), 'user_signup', array('id'));

    // Conditionally launch create table for user_payment.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Faktura savepoint reached.
    upgrade_block_savepoint(true, $newversion, 'faktura');
}
