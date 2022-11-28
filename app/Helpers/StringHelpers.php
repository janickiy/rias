<?php

namespace App\Helpers;

class StringHelpers
{
    /**
     * @param $data
     * @return array
     */
    public static function ObjectToArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = self::ObjectToArray($value);
            }
            return $result;
        }
        return $data;
    }

    /**
     * @param $el
     * @param bool $first
     * @return string
     */
    public static function tree($el, $first = true)
    {
        if (is_object($el)) $el = (array)$el;

        if ($el) {

            if ($first) {
                $out = '<ul id="tree-checkbox" class="tree-checkbox treeview">';
            } else {
                $out = '<ul>';
            }

            foreach ($el as $k => $v) {

                if (is_object($v)) $v = (array)$v;

                if ($v) {

                    $out .= "<li><strong> " . $k . " :</strong> ";

                    if (is_array($v)) {
                        $out .= self::tree($v, false);

                    } else {
                        $out .= $v;
                    }

                    $out .= "</li>";
                }
            }

            $out .= "</ul>";

            return $out;
        }
    }

    /**
     * @param $text
     * @return false|mixed|null|string|string[]
     */
    public static function slug($text, $toLower = true)
    {
        $text = trim($text);

        $tr = [
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ё" => "E",
            "Ж" => "J",
            "З" => "Z",
            "И" => "I",
            "Й" => "Y",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "H",
            "Ц" => "TS",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SCH",
            "Ъ" => "",
            "Ы" => "YI",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "YU",
            "Я" => "YA",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "e",
            "ж" => "j",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sch",
            "ъ" => "y",
            "ы" => "yi",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",
            "«" => "",
            "»" => "",
            "№" => "",
            "Ӏ" => "",
            "’" => "",
            "ˮ" => "",
            "_" => "-",
            "'" => "",
            "`" => "",
            "^" => "",
            "\." => "",
            "," => "",
            ":" => "",
            ";" => "",
            "<" => "",
            ">" => "",
            "!" => "",
            "\(" => "",
            "\)" => ""
        ];

        foreach ($tr as $ru => $en) {
            $text = mb_eregi_replace($ru, $en, $text);
        }

        if ($toLower) {
            $text = mb_strtolower($text);
        }

        $text = str_replace(' ', '-', $text);

        return $text;
    }

    /**
     * @param $str
     * @param int $chars
     * @return string
     */
    public static function shortText($str, $chars = 500)
    {
        $pos = strpos(substr($str, $chars), " ");
        $srttmpend = strlen($str) > $chars ? '...' : '';

        return substr($str, 0, $chars + $pos) . (isset($srttmpend) ? $srttmpend : '');
    }
}
