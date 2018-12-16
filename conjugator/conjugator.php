<?php

/**
 * Class Conjugator
 */
class Conjugator {
    private $Root = '';
    private $Type = null;

    private $U1SubjectCommand = 0;
    private $U2Subjective = 0;
    private $U3Potentiality = 0;
    private $U4Politeness = 0;
    private $U5Negation = 0;
    private $U6Connectivity = 0;
    private $U7Tense = 0;
    private $U8Question = 0;
    private $U9Command = 0;

    private $Output3Potentiality = null;

    private function CreateOutputObject($root = null, $type = null) {
        return new Input($root ? $root : $this->Root, $type ? $type : $this->Type);
    }

    /**
     * @param Input $input
     * @return Input
     */
    private function PresentAdjustment($input) {
        if ($this->U4Politeness == UCC_4N_CASUAL && $this->U5Negation == UCC_5T_AFFIRMATIVE) {
            return $input;
        } else if ($this->U4Politeness == UCC_4N_CASUAL && $this->U5Negation == UCC_5F_NEGATIVE) {
            return $input->Adjust(VERB_SOUND_STANDARD, 'a');
        } else if ($this->U4Politeness == UCC_4P_POLITE && $this->U5Negation == UCC_5T_AFFIRMATIVE) {
            return $input->Append('bii');
        } else if ($this->U4Politeness == UCC_4P_POLITE && $this->U5Negation == UCC_5F_NEGATIVE) {
            return $input->Append('bira');
        }
        return null;
    }

    /**
     * @param Input $input
     * @return Input
     */
    private function PastAdjustment($input) {
        if ($this->U4Politeness == UCC_4N_CASUAL && $this->U5Negation == UCC_5T_AFFIRMATIVE) {
            return $input->Adjust(VERB_SOUND_CONNECTIVE, 'a');
        } else if ($this->U4Politeness == UCC_4N_CASUAL && $this->U5Negation == UCC_5F_NEGATIVE) {
            return $input->Adjust(VERB_SOUND_STANDARD, 'aranta');
        } else if ($this->U4Politeness == UCC_4P_POLITE && $this->U5Negation == UCC_5T_AFFIRMATIVE) {
            return $input->Append('bita');
        } else if ($this->U4Politeness == UCC_4P_POLITE && $this->U5Negation == UCC_5F_NEGATIVE) {
            return $input->Append('bita');
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step1SubjectCommand($input) {
        switch ($this->U1SubjectCommand) {
            case UCC_1S_SUBJECT:
                return $this->Step2Subjective($input);
            case UCC_1C_COMMAND:
                return $this->Step9Command($input);
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step2Subjective($input) {
        switch ($this->U2Subjective) {
            case UCC_2A_ACTIVE:
                return $this->Step3Potentiality($input);
            case UCC_2P_PASSIVE:
                return $this->Step3Potentiality(
                    $input->Adjust(VERB_SOUND_STANDARD, 'ari', VERB_RT)
                );
            case UCC_2C_CAUSATIVE:
                return $this->Step3Potentiality(
                    $input->Adjust(VERB_SOUND_STANDARD, 'a', VERB_SS)
                );
            case UCC_2C_CAUSATIVE_PASSIVE:
                return $this->Step3Potentiality(
                    $input->Adjust(VERB_SOUND_STANDARD, 'asjari', VERB_RT)
                );
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step3Potentiality($input) {
        switch ($this->U3Potentiality) {
            case UCC_3N_NORMAL:
                $this->Output3Potentiality = $this->CreateOutputObject($input->root, $input->type);
                return $this->Step4Politeness($input);
            case UCC_3P_POTENTIAL:
                $input = $input->Adjust('standard', 'ari', VERB_RT);
                $this->Output3Potentiality = $this->CreateOutputObject($input->root, $input->type);
                return $this->Step4Politeness($input);
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step4Politeness($input) {
        switch ($this->U4Politeness) {
            case UCC_4N_CASUAL:
                return $this->Step5Negation($input);
            case UCC_4P_POLITE:
                return $this->Step5Negation(
                    $input->Adjust(VERB_SOUND_PRINCIPAL, 'abi', VERB_RT)
                );
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step5Negation($input) {
        switch ($this->U5Negation) {
            case UCC_5T_AFFIRMATIVE:
                return $this->Step6Connectivity($input);
            case UCC_5F_NEGATIVE:
                return $this->Step6Connectivity(
                    $input->Adjust(VERB_SOUND_STANDARD, 'a', VERB_RT)
                );
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step6Connectivity($input) {
        if ($this->U6Connectivity == UCC_6NN_VERB) {
            return $this->Step7Tense($input);
        } else if ($this->U6Connectivity == UCC_6AJ_ADJECTIVE) {
            if ($this->U5Negation == UCC_5T_AFFIRMATIVE) {
                return $input->Adjust(VERB_SOUND_PRINCIPAL, 'u')
                    ->Append('ru')->root;
            } else if ($this->U5Negation == UCC_5F_NEGATIVE) {
                return $input->root;
            }
            return null;
        } else {
            $is4N5T = $this->U4Politeness == UCC_4N_CASUAL && $this->U5Negation == UCC_5T_AFFIRMATIVE;
            if ($this->U6Connectivity == UCC_6PG_PROGRESSIVE) {
                return $is4N5T ?
                    $input->Adjust(VERB_SOUND_CONNECTIVE, 'i')->root
                    : $input->root . 'Nti';
            } else if ($this->U6Connectivity == UCC_6CN_CONNECTIVE) {
                return $is4N5T ?
                    $input->Adjust(VERB_SOUND_PRINCIPAL, 'i')->root
                    : $input->root . 'N';
            }
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step7Tense($input) {
        if ($this->U7Tense == UCC_7NW_PRESENT) {
            if ($this->U5Negation == UCC_5T_AFFIRMATIVE) {
                return $this->Step8Question($input->Adjust(VERB_SOUND_PRINCIPAL, 'u'));
            } else if ($this->U5Negation == UCC_5F_NEGATIVE) {
                return $this->Step8Question($input);
            }
        } else if ($this->U7Tense == UCC_7NC_PROGRESSIVE) {
            return $this->Step8Question($this->PresentAdjustment(
                $this->Output3Potentiality->Adjust(VERB_SOUND_CONNECTIVE, 'oo', VERB_RT)
            ));
        } else if ($this->U7Tense == UCC_7NP_PERFECT) {
            return $this->Step8Question($this->PresentAdjustment(
                $this->Output3Potentiality->Adjust(VERB_SOUND_CONNECTIVE, 'ee', VERB_RT)
            ));
        } else if ($this->U7Tense == UCC_7PS_PAST) {
            if ($this->U5Negation == UCC_5F_NEGATIVE) {
                $input->Append('N');
            }
            return $this->Step8Question($input->Adjust(VERB_SOUND_CONNECTIVE, 'a', VERB_RT));
        } else if ($this->U7Tense == UCC_7PP_PAST_PROGRESSIVE) {
            return $this->Step8Question($this->PastAdjustment(
                $this->Output3Potentiality->Adjust(VERB_SOUND_CONNECTIVE, 'u', VERB_RT)
            ));
        } else if ($this->U7Tense == UCC_7PC_PAST_CONTINUOUS) {
            return $this->Step8Question($this->PastAdjustment(
                $this->Output3Potentiality->Adjust(VERB_SOUND_CONNECTIVE, 'oo', VERB_RT)
            ));
        } else if ($this->U7Tense == UCC_7PF_PAST_PERFECT) {
            return $this->Step8Question($this->PastAdjustment(
                $this->Output3Potentiality->Adjust(VERB_SOUND_CONNECTIVE, 'ee', VERB_RT)
            ));
        } else if ($this->U7Tense == UCC_7IV_INVITATION) {
            if ($this->U4Politeness == UCC_4P_POLITE) {
                return null;
            } else if ($this->U4Politeness == UCC_4N_CASUAL) {
                if ($this->U5Negation == UCC_5T_AFFIRMATIVE) {
                    return $this->Output3Potentiality->Adjust(VERB_SOUND_STANDARD, 'a')->root;
                } else if ($this->U5Negation == UCC_5F_NEGATIVE) {
                    return $this->Output3Potentiality->Adjust(VERB_SOUND_STANDARD, 'ani')->root;
                }
            }
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step8Question($input) {
        if ($this->U8Question == UCC_8TM_TERMINATION) {
            return $input->root . 'N';
        } else if ($this->U8Question == UCC_8QT_QUESTION) {
            if ($this->U4Politeness == UCC_4N_CASUAL
                && $this->U5Negation == UCC_5F_NEGATIVE
                && ($this->U6Connectivity & (UCC_7NW_PRESENT | UCC_7NC_PROGRESSIVE | UCC_7PF_PAST_PERFECT))) {
                return $input->root . 'ni';
            } else if ($this->U4Politeness == UCC_4P_POLITE) {
                return $input->root . 'mi';
            } else {
                return $input->root . 'mi';
            }
        }
        return null;
    }

    /**
     * @param Input $input
     * @return null|string
     */
    private function Step9Command($input) {
        switch ($this->U9Command) {
            case UCC_9C_COMMAND:
                return $input->Adjust(VERB_SOUND_STANDARD, 'eeN')->root;
            case UCC_9P_PROHIBITED:
                return $input->Adjust(VERB_SOUND_STANDARD, 'una')->root;
        }
        return null;
    }


    /**
     * Conjugator constructor.
     * @param string $verbType
     * @param string $root
     */
    public function __construct($verbType, $root = '') {
        global $VERB_ROOT_TAILS;
        if (!isset($VERB_ROOT_TAILS[$verbType])) {
            error_log("Unknown verb type {$verbType}");
            die;
        }
        $this->Type = $VERB_ROOT_TAILS[$verbType];
        $this->Root = $root;
        $this->Output3Potentiality = new Input('', null);
    }

    /**
     * @param int $u1SubjectCommand
     * @param int $u2Subjective
     * @param int $u3Potentiality
     * @param int $u4Politeness
     * @param int $u5Negation
     * @param int $u6Connectivity
     * @param int $u7Tense
     * @param int $u8Question
     * @param int $u9Command
     * @return string
     */
    public function Conjugate(
        $u1SubjectCommand = 0,
        $u2Subjective = 0,
        $u3Potentiality = 0,
        $u4Politeness = 0,
        $u5Negation = 0,
        $u6Connectivity = 0,
        $u7Tense = 0,
        $u8Question = 0,
        $u9Command = 0
    ) {
        $this->U1SubjectCommand = $u1SubjectCommand;
        $this->U2Subjective = $u2Subjective;
        $this->U3Potentiality = $u3Potentiality;
        $this->U4Politeness = $u4Politeness;
        $this->U5Negation = $u5Negation;
        $this->U6Connectivity = $u6Connectivity;
        $this->U7Tense = $u7Tense;
        $this->U8Question = $u8Question;
        $this->U9Command = $u9Command;

        return $this->Step1SubjectCommand(new Input($this->Root, $this->Type));
    }
}
