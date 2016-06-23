<?php


class block_faktura_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

// Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('faku', 'block_faktura'));

// A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('faktura_block_title', 'block_faktura'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_faktura');
            } else {
                $this->title = $this->config->title;
            }

            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_faktura');
            }
        }
    }
}