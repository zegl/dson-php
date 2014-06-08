dson-php
======

DSON encoder/decoder for PHP

![Doge](http://dogeon.org/doge.gif)


### What is dson-php?

dson-php is a simple DSON <http://dogeon.org> encoder
and decoder. It is a pure PHP-implementatin without any special dependencies.

### How to use?
#### DSON::encode($in)

```php
$example = array(
    "many" => "wow",
    "such" => array("foo", "doge", "inu")
);

echo DSON::encode($example);
```

```
such "many" is "wow" ! "such" is so "foo" and "doge" and "inu" many wow
```

#### DSON::decode($str, $assoc = false)
```php
$res = DSON::decode('such "many" is "wow" ! "such" is so "foo" and "doge" and "inu" many wow');
```

```
object(stdClass)#1 (2) {
  ["many"]=>
  string(3) "wow"
  ["such"]=>
  array(3) {
    [0]=>
    string(3) "foo"
    [1]=>
    string(4) "doge"
    [2]=>
    string(3) "inu"
  }
}
```

Setting `$assoc = true` will generate the output as an associative array instead, (compare to <http://php.net/json_decode>)

```php
$res = DSON::decode('such "many" is "wow" ! "such" is so "foo" and "doge" and "inu" many wow', true);
```

```
array(2) {
  ["many"]=>
  string(3) "wow"
  ["such"]=>
  array(3) {
    [0]=>
    string(3) "foo"
    [1]=>
    string(4) "doge"
    [2]=>
    string(3) "inu"
  }
}
```
