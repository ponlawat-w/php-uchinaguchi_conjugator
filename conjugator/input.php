<?php

class Input {
    /**
     * @var string $root
     */
    public $root;
    /**
     * @var VerbType $type
     */
    public $type;

    public function __construct($root, $type) {
        $this->root = $root;
        $this->type = $type;
    }

    private function AdjustPrincipalU() {
        if ($this->type->type == VERB_ROOT_CONSONANT) {
            $this->root .= $this->type->principal . 'u';
        } else if ($this->type->type == VERB_ROOT_VOWEL_I) {
            $this->root .= 'i';
        }
        return $this;
    }

    public function Adjust($sound, $append = '', $newType = null) {
        if ($sound == VERB_SOUND_PRINCIPAL && $append == 'u') {
            $this->AdjustPrincipalU();
        } else {
            $this->root .= $this->type->$sound . $append;
        }
        if ($newType) {
            global $VERB_ROOT_TAILS;
            if ($newType instanceof VerbType) {
                $this->type = $newType;
            } else if (isset($VERB_ROOT_TAILS[$newType])) {
                $this->type = $VERB_ROOT_TAILS[$newType];
            } else {
                return null;
            }
        }
        return $this;
    }

    public function Append($append) {
        $this->root .= $append;
        return $this;
    }
}
