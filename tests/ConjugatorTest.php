<?php
class ConjugatorTest extends \PHPUnit\Framework\TestCase
{
    private $kacuN_conjugator;
    private $tacuN_conjugator;
    private $sun_conjugator;
    private $Nzun_conjugator;
    private $tuiN_conjugator;
    private $ciiN_conjugator;
    private $iN_conjugator;

    public function __construct() {
        parent::__construct();
        $this->kacuN_conjugator = new Conjugator(VERB_KC, 'ka');
        $this->tacuN_conjugator = new Conjugator(VERB_TQC, 'ta');
        $this->sun_conjugator = new Conjugator(VERB_SS);
        $this->Nzun_conjugator = new Conjugator(VERB_DC, 'N');
        $this->tuiN_conjugator = new Conjugator(VERB_RT, 'tu');
        $this->ciiN_conjugator = new Conjugator(VERB_RQC, 'ci');
        $this->iN_conjugator = new Conjugator(VERB_N, 'i');
    }

    public function testPresent() {
        $this->assertEquals('kacuN', $this->kacuN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));
        $this->assertEquals('suN', $this->sun_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));
        $this->assertEquals('tuiN', $this->tuiN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));
        $this->assertEquals('iN', $this->iN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));

        // Potential
        $this->assertEquals('tatariiN', $this->tacuN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3P_POTENTIAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));

        // Passive
        $this->assertEquals('cirariiN', $this->ciiN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2P_PASSIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6NN_VERB,
            UCC_7NW_PRESENT, UCC_8TM_TERMINATION
        ));
    }

    public function testAdjective() {
        $this->assertEquals('Nzuru', $this->Nzun_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6AJ_ADJECTIVE
        ));
        $this->assertEquals('tuiru', $this->tuiN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6AJ_ADJECTIVE
        ));

        // Potential
        $this->assertEquals('irariiru', $this->iN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2A_ACTIVE, UCC_3P_POTENTIAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6AJ_ADJECTIVE
        ));

        // Passive
        $this->assertEquals('kakariiru', $this->kacuN_conjugator->Conjugate(
            UCC_1S_SUBJECT, UCC_2P_PASSIVE, UCC_3N_NORMAL,
            UCC_4N_CASUAL, UCC_5T_AFFIRMATIVE, UCC_6AJ_ADJECTIVE
        ));
    }
}
