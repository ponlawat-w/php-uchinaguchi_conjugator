<?php

$VERB_ROOT_TAILS = [
    VERB_KC => new VerbType(VERB_ROOT_CONSONANT, 'k', 'c', 'c'),
    VERB_KZ => new VerbType(VERB_ROOT_CONSONANT, 'k', 'c', 'z'),
    VERB_GZ => new VerbType(VERB_ROOT_CONSONANT, 'g', 'z', 'z'),
    VERB_TQC => new VerbType(VERB_ROOT_CONSONANT, 't', 'c', 'qc'),
    VERB_TC => new VerbType(VERB_ROOT_CONSONANT, 't', 'c', 'c'),
    VERB_SS => new VerbType(VERB_ROOT_CONSONANT, 's', 's', 's'),
    VERB_SC => new VerbType(VERB_ROOT_CONSONANT, 's', 's', 'c'),
    VERB_BD => new VerbType(VERB_ROOT_CONSONANT, 'b', 'b', 'd'),
    VERB_MD => new VerbType(VERB_ROOT_CONSONANT, 'm', 'm', 'd'),
    VERB_DT => new VerbType(VERB_ROOT_CONSONANT, 'd', 'z', 't'),
    VERB_DC => new VerbType(VERB_ROOT_CONSONANT, 'd', 'z', 'c'),
    VERB_RQT => new VerbType(VERB_ROOT_VOWEL_I, 'r', null, 'qt'),
    VERB_RT => new VerbType(VERB_ROOT_VOWEL_I, 'r', null, 't'),
    VERB_RQC => new VerbType(VERB_ROOT_VOWEL_I, 'r', null, 'qc'),
    VERB_RC => new VerbType(VERB_ROOT_VOWEL_I, 'r', null, 'c'),
    VERB_N => new VerbType(VERB_ROOT_VOWEL_N, 'r', null, 't'),
    VERB_YT_IRREGULAR_MIT => new VerbType(VERB_ROOT_VOWEL_I, 'm', null, 't'),
    VERB_IRREGULAR_YUN => new VerbType(VERB_ROOT_CONSONANT, 'k', 'c', 'c'),
    VERB_IRREGULAR_CUUN => new VerbType(VERB_ROOT_CONSONANT, 'j', 'j', 'c')
];
