<?php

/*------------------------------------------------------------
    Innowell Faktura-block for Moodle
    by Theis Thompson - theisthompson@gmail.com

    Inner Class Structure:
    - Init
    - Get Content
------------------------------------------------------------*/

defined('MOODLE_INTERNAL') || die();

/*-------------------------------------------------------------
    Faktura Block Class
-------------------------------------------------------------*/

class block_faktura extends block_base {

    public function init() {
        $this->title = get_string('faktura_block_title', 'block_faktura');
    }


    public function get_content() {
        //global $CFG, $USER, $DB, $OUTPUT, $PAGE; // Who knows?
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = 'Du skylder os penge!';

        return $this->content;
    }

}