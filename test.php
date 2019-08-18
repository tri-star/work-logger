<?php

$drivers = [
    new Driver(),
    new Driver(),
    ];

function getResult(): array
{
    global $drivers;
    $array =  [
        $drivers[0]->getResult(),
        $drivers[1]->getResult(),
    ];
    return $array;
}


class Driver
{
    public function getResult(): array
    {
        $result = [ 'a' => str_repeat('a', 1000)];
        return array_merge([], $result);
    }
}


$a = [];

for ($i=0; $i <= 9000000; $i++) {
    $result = getResult();
    foreach ($result as $row) {
        $a = $row;
    }
}
var_dump($a);

echo number_format(memory_get_peak_usage(true)) . "Bytes\n";
