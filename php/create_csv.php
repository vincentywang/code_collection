<?php

header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="sample.csv"');

$data = array(
        'aaa,bbb,ccc,dddd',
        '123,456,789',
        '"aaa","bbb"'
);

$fp = fopen('php://output', 'w');
foreach ( $data as $line ) {
    $val = explode(",", $line);
    fputcsv($fp, $val);
}
fclose($fp);


?> 
