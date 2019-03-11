<?php
ini_set('memory_limit','2000M');
$a = [];

for ($i=0;$i<1000000;$i++) {
    $a[] = $i;
}
echo '100万レコード：'.(memory_get_usage(true)/1024/1024)."MB\n";
//php5系と7系でのメモリ使用量の差を確認

$start_memory = memory_get_usage(true);

foreach ($a as $key => &$value) {
    $value = $value;    
}
unset($value);
echo "foreach参照渡し\n";
echo ((memory_get_usage(true)-$start_memory)/1024/1024)."MB\n";


$start_memory = memory_get_usage(true);
$b = [];
foreach ($a as $key => $value) {
    $b[$key] = $value;
}
echo "foreach配列コピー\n";
echo ((memory_get_usage(true)-$start_memory)/1024/1024)."MB\n";

$start_memory = memory_get_usage(true);

$cnt = count($a);
for ($i=0;$i<$cnt;$i++) {
    $a[$i] = $a[$i];
}
echo "for(キーが連続した数値のときのみ)\n";
echo ((memory_get_usage(true)-$start_memory)/1024/1024)."MB\n";


/*

php5系時のメモリ増加対策としてのforeachの参照渡しが
php7系だとメモリ増加対策にならないので検証。

結論。大量データを扱わないならphp7系でループの仕方を意識する必要はない。
大量データ取扱時はforで回せるようにデータを作る

php5.6実行時
100万レコード:  138.25MB

foreach参照渡し時増加量: 0MB
foreach配列コピー時増加量: 92.25MB
for時増加量: 0MB

php7実行時
100万レコード：34.00MB
foreach参照渡し時増加量: 22MB
foreach配列コピー時増加量: 32.00MB
for時増加量: 0MB

そもそものメモリ使用量が1/4位まで少なくなっている。
php7になった際に変数は16byteで管理し、参照渡しが24byteで管理されるようになったので、
foreach時にメモリ使用量が1レコードあたり8byte増加する
100万レコード単位のものを複数回ループ処理させるようなことがあるとこの影響が無視できなくなる
*/
