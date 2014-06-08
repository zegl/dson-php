dson-php
======

DSON encoder/decoder for PHP

![Doge](http://dogeon.org/doge.gif)


### What is dson-php?

dson-php is a simple DSON <http://dogeon.org> encoder
and decoder. It is a pure PHP-implementatin without any special dependencies.

### How to use?

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
