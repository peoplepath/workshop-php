### Zadání
Napiště "univerzální" program, který přečte libovolně dlouhý textový soubor.
Řádek po řádku bude aplikovat uživatelské filtry a dekorátory. Výstupem programu
bude počet stejných (upravených) řádků a jejich četností.

Použijte co nejvíce vlastností moderního PHP. Doporučení:
- [Iterables](http://php.net/manual/en/language.types.iterable.php)
- [Anonymous functions](http://php.net/manual/en/functions.anonymous.php), especially [Callables](http://php.net/manual/en/language.types.callable.php)
- [Types](http://php.net/manual/en/migration70.new-features.php#migration70.new-features.scalar-type-declarations)
- And [more](http://php.net/manual/en/langref.php)

#### Bonus
Upravte program tak, aby vypisoval průběžný stav nekonečného streamu.

### Příklad
```bash
php old.php example.log
```

#### Vstupní soubor
```
[2018-03-13 12:16:10] test.DEBUG: Test message [] []
[2018-03-13 12:16:10] test.ERROR: Test message [] []
[2018-03-13 12:16:10] test.WARNING: Test message [] []
[2018-03-13 12:16:10] test.WARNING: Test message [] []
[2018-03-13 12:16:10] test.INFO: Test message [] []
[2018-03-13 12:16:10] test.NOTICE: Test message [] []
[2018-03-13 12:16:10] test.EMERGENCY: Test message [] []
[2018-03-13 12:16:10] test.ALERT: Test message [] []
[2018-03-13 12:16:10] test.ERROR: Test message [] []
[2018-03-13 12:16:10] test.NOTICE: Test message [] []
```

#### Výstup
```
error: 2
warning: 2
notice: 2
info: 1
emergency: 1
alert: 1
```

#### Implementace ve "starém" PHP
```php
<?php
$pattern = '/test\.(\w+)/';

// read and parse file
$log  = file_get_contents($argv[1]);
$rows = explode(PHP_EOL, $log);

// build stats
$stats = array();
foreach ($rows as $row) {
    // decorator: extract log level
    if (preg_match($pattern, $row, $matches)) {
        $level = strtolower($matches[1]);

        // filter: do not accept DEBUG
        if ($level != 'debug') {
            if (array_key_exists($level, $stats)) {
                $stats[$level]++;
            } else {
                $stats[$level] = 1;
            }
        }
    }
}

// show stats
arsort($stats);
foreach ($stats as $level => $count) {
    echo "$level: $count" . PHP_EOL;
}
```
