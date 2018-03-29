<?php
// this is simple example that you can write pure procedural programming
// https://en.wikipedia.org/wiki/Procedural_programming

// namespaces are modules
namespace Shop\Items {
    // functions are procedures :-)
    function find(string $name): array {
        // associative arrays are records
        return [
            'name' => $name,
            'price' => rand(1, 999) / 10.0,
        ];
    }
}

namespace Shop\Invoice {
    function bill(array $basket): string {
        return implode(PHP_EOL, array_map(function ($item) {
            return "{$item['name']} ... {$item['price']}";
        }, $basket['items']));
    }
}

namespace {
    // and you can call a procedures :-)
    $basket['items'][] = Shop\Items\find('Socks');
    $basket['items'][] = Shop\Items\find('Shorts');

    echo Shop\Invoice\bill($basket);
}

