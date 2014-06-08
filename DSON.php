<?php

class DSON
{
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
     * DSON::encode()
     *
     * Translates your DSON into php types
     *
     * @param mixed $in
     * @param bool $assoc - When TRUE, returned objects will be converted into associative arrays.
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

    private static function translate($in, $replacements, $to_doge = false)
    {
        $in_string = false;
        $is_escaping = false;

        $quote = '"';
        $current_char = '';
        $current_token = '';
        $current = '';
        $out = '';

        $in_object = false;
        $in_array = false;

        $length = strlen($in);
        for ($i = 0; $i < $length; $i++)
        {
            $current_char = substr($in, $i, 1);

            //var_dump($current_char);

            if ($in_string === true)
            {
                // such end of string
                // many reset and push to output
                if ($current_char === $quote && $is_escaping === false)
                {
                    $out .= $quote . $current . $quote;

                    if ($to_doge) {
                        $out .= " ";
                    }

                    $in_string = false;
                    $end_string = false;
                    $current = '';

                    continue;
                }

                $is_escaping = ($current_char === "\\");
                $current .= $current_char;

                continue;
            }

            // many beginning of string
            if ($current_char === $quote)
            {
                $in_string = true;
                $current = ''; // such reset

                continue;
            }

            // such token addition
            if (strlen(trim($current_char)) > 0)
            {
                $current_token .= $current_char;

                if ($current_char === "{")
                {
                    $in_object = true;
                    $in_array = false;
                }

                if ($current_char === "[")
                {
                    $in_array = true;
                    $in_object = false;
                }

            } else {

                if (strlen($current_token) > 0) {

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
            }

            var_dump($current_token);

            // very replacemtnt matching
            $tmp_current_token = $current_token;
            if ($in_array) {
                $tmp_current_token .= '_array';
            }
            if ($in_object) {
                $tmp_current_token .= '_object';
            }


            if (isset($replacements[$tmp_current_token]) ||
                isset($replacements[$current_token]))
            {

                if (isset($replacements[$tmp_current_token])) {
                    $token = $replacements[$tmp_current_token];
                    //var_dump($tmp_current_token);
                } else {
                    $token = $replacements[$current_token];
                }

                if (is_array($token))
                {
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
