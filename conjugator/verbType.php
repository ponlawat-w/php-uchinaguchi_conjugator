<?php
class VerbType {
    public $type;
    public $standard;
    public $principal;
    public $connective;

    public function __construct($type, $standard, $principal, $connective) {
        $this->type = $type;
        $this->standard = $standard;
        $this->principal = $principal;
        $this->connective = $connective;
    }
}
