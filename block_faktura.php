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

        $this->content         =  new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';

        return $this->content;
    }


    // Allow multiple instances
    public function instance_allow_multiple() {
        return false; // Doesn't matter - but let's show that this exists.
    }

}