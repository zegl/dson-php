<?php

namespace zegl\dson;

/**
 * @author Gustav Westling <hej@gustav.tv>
 * @version 1.0
 */
class DSON
{
    /**
     * DSON::encode()
     *
     * Translates your php-objects into DSON
     *
     * @param mixed $in
     *
     * @return string
     */
    public static function encode($in)
    {
        $replacements = array(
            "{" => "such",
            "}" => "wow",
            ":" => "is",
            "[" => "so",
            "]" => "many",

            ",_array" => array("and", "also"),
            ",_object" => array(",", ".", "!", "?"),

            "true" => "yes",
            "false" => "no",
            "null" => "empty"
        );

        $json = json_encode($in);

        $out = self::translate($json, $replacements, true);

        return $out;
    }

    /**
     * DSON::decode()
     *
     * Translates your DSON into php types
     *
     * @param string $in
     * @param bool   $assoc - When TRUE, returned objects will be converted into associative arrays.
     *
     * @return mixed
     */
    public static function decode($in, $assoc = false)
    {
        // such magic keywords
        $replacements = array(
            "such" => "{",
            "wow" => "}",
            "is" => ":",
            "so" => "[",
            "many" => "]",
            "and" => ",",
            "also" => ",",

            "yes" => "true",
            "no" => "false",
            "empty" => "null",

            "." => ",",
            "," => ",",
            "!" => ",",
            "?" => ","
        );

        $out = self::translate($in, $replacements);

        return json_decode($out, $assoc);
    }

    /**
     * DSON::translate()
     *
     *
     * @param string $in
     * @param array  $replacements
     * @param bool   $to_doge
     *
     * @return string
     */
    private static function translate($in, $replacements, $to_doge = false)
    {
        /*
            Definition of variables
        */
        $in_string = false;
        $is_escaping = false;

        $quote = '"';
        $current_char = '';
        $current_token = '';
        $current = '';
        $out = '';

        $in_object = false;
        $in_array = false;

        /*
            Main loop
            Loop one character at a time
        */
        $length = strlen($in);
        for ($i = 0; $i < $length; $i++) {
            $current_char = substr($in, $i, 1);

            /*
                $in_string
            */
            if ($in_string === true) {
                /*
                    End of string detection
                    Reset and push out output
                */
                if ($current_char === $quote && $is_escaping === false) {
                    $out .= $quote . $current . $quote;

                    if ($to_doge) {
                        $out .= " ";
                    }

                    $in_string = false;
                    $end_string = false;
                    $current = '';

                    continue;
                }

                /*
                    Escaping detection
                */
                $is_escaping = ($current_char === "\\");
                $current .= $current_char;

                continue;
            }

            /*
                String detection
            */
            if ($current_char === $quote) {
                // such reset
                $in_string = true;
                $current = '';

                continue;
            }

            // such token addition
            if (strlen(trim($current_char)) > 0) {
                $current_token .= $current_char;

                if ($current_char === "{") {
                    $in_object = true;
                    $in_array = false;
                }

                if ($current_char === "[") {
                    $in_array = true;
                    $in_object = false;
                }

            }

            /*
                $current_chat is a whitespace and $current_token is sent
                    OR
                $current_token is set and is numeric, but the next character in $in is not a number, but only when transforming to doge
            */
            if (
                (strlen(trim($current_char)) === 0 && strlen($current_token) > 0) ||
                ($to_doge === true && strlen($current_token) > 0 && is_numeric($current_token) && !is_numeric(substr($in, $i+1, 1)))
            ) {

                // 42very3 => 42e3 => 42000
                $math = (float) str_replace("very", "e", $current_token);

                if ($math) {
                    $out .= $math;
                } else {
                    $out .= $quote . $current_token . $quote;
                }

                if ($to_doge) {
                    $out .= " ";
                }

                $in_string = false;
                $end_string = false;
                $current = '';
                $current_token = '';

                continue;
            }

            /*
                Separators in arrays and objects are different
            */
            $tmp_current_token = $current_token;
            if ($in_array) {
                $tmp_current_token .= '_array';
            }
            if ($in_object) {
                $tmp_current_token .= '_object';
            }

            /*
                very replacement translation / matching
            */
            if (isset($replacements[$tmp_current_token]) ||
                isset($replacements[$current_token]))
            {

                if (isset($replacements[$tmp_current_token])) {
                    $token = $replacements[$tmp_current_token];
                } else {
                    $token = $replacements[$current_token];
                }

                // Select one of multiple choises
                if (is_array($token)) {
                    $token = $token[rand(0, count($token) - 1)];
                }

                $out .= $token;

                if ($to_doge) {
                    $out .= " ";
                }

                $current_token = '';
            }
        }

        return trim($out);
    }
}
