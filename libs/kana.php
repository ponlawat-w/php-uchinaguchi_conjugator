<?php

const VOWELS = ['a' => 0, 'i' => 1, 'u' => 2, 'e' => 3, 'o' => 4, '' => 5];
const VOWEL_VALUES = ['a', 'i', 'u', 'e', 'o', ''];

const KANAS = [
    ''   => ['あ',  'い',    'う',   'え',  'お', null],
    'j'  => ['や',  'ゐ',    'ゆ',   'ゑ',  'よ',  null],
    'y'  => ['や',  'ゐ',    'ゆ',   'ゑ',  'よ',  null],
    'w'  => ['わ',  'うぃ',  'をぅ', 'うぇ', 'を',  null],
    'h'  => ['は',  'ひ',    'ふ',   'へ',  'ほ',   null],
    'hj' => ['ひゃ', 'ひぃ',  'ひゅ', 'ひぇ', 'ひょ', null],
    'hw' => ['ふぁ', 'ふぃ', 'ふ',   'ふぇ', 'ふぉ', null],
    'k'  => ['か',   'き',   'く',   'け',  'こ',   null],
    'kw' => ['くゎ', 'くぃ',  'くぅ', 'くぇ', 'くぉ',  null],
    'g'  => ['が',   'ぎ',   'ぐ',   'げ',   'ご',  null],
    'gw' => ['ぐゎ', 'ぐぃ', 'ぐぅ',   'ぐぇ', 'ぐぉ',  null],
    't'  => ['た',   'てぃ', 'とぅ', 'て',   'と',  null],
    'd'  => ['だ',   'でぃ', 'どぅ', 'で',   'ど',  null],
    'c'  => ['ちゃ', 'ち',   'ちゅ', 'ちぇ', 'ちょ', null],
    'ch' => ['ちゃ', 'ち',   'ちゅ', 'ちぇ', 'ちょ', null],
    's'  => ['さ',   'し',   'す',   'しぇ', 'そ',  null],
    'sj' => ['しゃ', 'しぃ',   'しゅ', 'しぇ', 'しょ', null],
    'sh' => ['しゃ', 'しぃ',   'しゅ', 'しぇ', 'しょ', null],
    'z'  => ['じゃ', 'じ',   'じゅ', 'じぇ', 'じょ', null],
    'n'  => ['な',   'に',   'ぬ',   'ね',   'の',  'ん'],
    'nj' => ['にゃ', 'にぃ',   'にゅ', 'にぇ', 'にょ', null],
    'r'  => ['ら',   'り',   'る',   'れ',   'ろ',  null],
    'rj'  => ['りゃ', 'りぃ', 'りゅ', 'りぇ', 'りょ',  null],
    'b'  => ['ば',   'び',   'ぶ',   'べ',   'ぼ',  null],
    'p'  => ['ぱ',   'ぴ',   'ぷ',   'ぺ',   'ぽ',  null],
    'bj' => ['びゃ', 'びぃ',   'びゅ', 'びぇ',   'びょ', null],
    'm'  => ['ま',   'み',   'む',   'め',   'も',  null],
    'mj' => ['みゃ', 'みぃ',   'みゅ', 'みぇ', 'みょ', null],
    'q'  => [null,   null,   null,   null,  null,  'っ']
];

const PARTIAL_KANAS = ['ぁ', 'ぃ', 'ぅ', 'ぇ', 'ぉ', 'ゃ', 'ゅ', 'ょ'];
const ROMAJI_VOWELS = ['a', 'e', 'i', 'o', 'u'];

/**
 * @param string $kana
 * @return bool
 */
function kana_is_glottal_stop($kana) {
    return $kana == 'あ'
        || $kana == 'い'
        || $kana == 'う'
        || $kana == 'え'
        || $kana == 'お';
}

/**
 * @param string $kana
 * @return int|string
 */
function kana_get_vowel($kana) {
    foreach (KANAS as $kanaRow) {
        foreach ($kanaRow as $vowelIndex => $kanaInTable) {
            if ($kanaInTable == $kana) {
                return $vowelIndex;
            }
        }
    }
    return -1;
}

/**
 * @param string $kana1
 * @param string $kana2
 * @return bool
 */
function kana_should_stretch($kana1, $kana2) {
    if (!$kana1 || !$kana2) {
        return false;
    }

    if (kana_is_glottal_stop($kana2) && kana_get_vowel($kana1) == kana_get_vowel($kana2)) {
        return true;
    }

    return false;
}

/**
 * @param string $alphabet
 * @param string $vowel
 * @return string
 */
function kana($alphabet, $vowel) {
    $head = '';
    $tail = '';
    if ($alphabet && (mb_substr($alphabet, 0, 1) == 'q' || mb_substr($alphabet, 0, 1) == 'Q')) {
        $alphabet = substr($alphabet, 1);
        $head = 'っ';
    }
    return $head . KANAS[$alphabet][VOWELS[$vowel]] . $tail;
}

/**
 * @param string $kana1
 * @param string $kana2
 * @return string
 */
function kana_concat($kana1, $kana2) {
    return kana_should_stretch($kana1, $kana2) ? $kana1 . 'ー' : $kana1 . $kana2;
}

/**
 * @param string[] ...$kanas
 * @return string
 */
function kana_implode(...$kanas) {
    return kana_implode_array($kanas);
}

/**
 * @param string[] $kanas
 * @return string
 */
function kana_implode_array($kanas) {
    $result = '';
    $previousKana = null;
    foreach ($kanas as $kana) {
        if ($previousKana) {
            $result .= kana_should_stretch($previousKana, $kana) ? 'ー' : $kana;
        } else {
            $result .= $kana;
        }

        $previousKana = $kana;
    }

    return $result;
}

/**
 * @param string $str
 * @return string[]
 */
function kana_explode($str) {
    $kanas = [];
    $length = mb_strlen($str);
    for ($i = 0; $i < $length; $i++) {
        $kana = mb_substr($str, $i, 1);
        if ($kana == 'ー' && count($kanas)) {
            $kana = KANAS[''][kana_get_vowel($kanas[count($kanas) - 1])];
        }

        if ($i < $length - 1) {
            $nextChar = mb_substr($str, $i + 1, 1);
            if (in_array($nextChar, PARTIAL_KANAS)) {
                $kana .= $nextChar;
                $i++;
            }
        }

        $kanas[] = $kana;
    }

    return $kanas;
}

/**
 * @param string $str
 * @return string
 */
function kana_reformat($str) {
    return kana_implode_array(kana_explode($str));
}

/**
 * @param string $romaji
 * @return string[]
 */
function partial_romaji_to_kana($romaji) {
    $kanas = [];

    $alphabet = '';
    for ($i = 0; $i < strlen($romaji); $i++) {
        $char = strtolower($romaji[$i]);
        if ($char != '?' && ($char < 'a' || $char > 'z')) {
            continue;
        }

        if (in_array($char, ROMAJI_VOWELS)) {
            $kanas[] = kana($alphabet, $char);
            $alphabet = '';
            continue;
        } else {
            if ($alphabet && $alphabet == $char) {
                $kanas[] = 'っ';
                $alphabet = '';
            }
            $alphabet .= $char;
        }

        if ($alphabet == 'q') {
            $kanas[] = 'っ';
            $alphabet = '';
            continue;
        } else if ($alphabet == 'n') {
            $nextChar = $i < strlen($romaji) - 1 ? strtolower($romaji[$i + 1]) : null;
            if (!$nextChar || !in_array($nextChar, ROMAJI_VOWELS)) {
                $kanas[] = 'ん';
                $alphabet = '';
                continue;
            }
        }
    }

    return $kanas;
}

/**
 * @param string $romaji
 * @return string
 */
function kana_from_romaji($romaji) {
    $kana = '';
    $glottalSyllables = explode('?', $romaji);
    foreach ($glottalSyllables as $str) {
        $kana .= kana_implode_array(partial_romaji_to_kana($str));
    }

    return $kana;
}

/**
 * @param string $kana
 * @return string
 */
function romaji_from_kana($kana) {
    foreach (KANAS as $alphabet => $kanaRow) {
        foreach ($kanaRow as $vowelValue => $kanaValue) {
            if ($kanaValue == $kana) {
                $vowel = VOWEL_VALUES[$vowelValue];
                return "{$alphabet}{$vowel}";
            }
        }
    }
    return '';
}

/**
 * @param string $str
 * @return string
 */
function romaji_from_kanas($str) {
    return implode('',
        array_map(function($kana) { return romaji_from_kana($kana); },
            kana_explode($str)));
}
