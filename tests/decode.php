<?php

require_once '../DSON.php';

$tests = array(
        array(
            'such "foo" is "bar" wow',
            '{"foo":"bar"}'
        ),
        array(
            'such "foo" is so "bar" and "baz" also "fizzbuzz" many wow',
            '{"foo":["bar","baz","fizzbuzz"]}'
        ),
        array(
            'such "foo" is 42very3 wow',
            '{"foo":42000}'
        ),
        array(
            'such " \"such " is "bar" wow',
            '{" \"such ":"bar"}'
        ),
        array(
            'such
                        " such " is "very bar" wow
            ',
            '{" such ":"very bar"}'
        ),
        array(
            'such "foo" is empty wow',
            '{"foo":null}'
        ),
        array(
            'such "foo" is so no and yes many wow',
            '{"foo":[false,true]}'
        ),
        array(
            'such "foo" is so "yes" and "no" many wow',
            '{"foo":["yes","no"]}'
        ),
        array(
            'such "foo" is "bar". "doge" is "shibe" wow',
            '{"foo":"bar","doge":"shibe"}'
        ),
        array(
            'such "foo" is such "shiba" is "inu", "doge" is yes wow wow',
            '{"foo":{"shiba":"inu","doge":true}}'
        ),
        array(
            'such "foo" is such "shiba" is "inu". "doge" is such "good" is yes! "a" is empty ? "b" is no wow wow wow',
            '{"foo":{"shiba":"inu","doge":{"good":true,"a":null,"b":false}}}'
        ),
        array(
            '"test"',
            '"test"'
        )
);

foreach ($tests as $test)
{
    $res = DSON::decode($test[0]);

    if ($res === json_decode($test[1], true))
    {
        echo "OK!";
    } else {
        var_dump($test);
        var_dump($res);
    }

    echo "\n";
}
