<?php
$mem = new Memcache;
$is_add = $mem->
addServer('10.14.85.166', 11211, true, 1, 1, 15, true); // retrt_interval=15
$is_add = $mem->addServer('10.14.85.167', 11211, true, 1, 1, 15, true); // retrt_interval=15
$is_set = $mem->set('key1', '中华人民共和国');
$is_set = $mem->set('key2', array('中华人民共和国'));
var_dump($is_add,$is_set);
var_dump($mem->get('key1'));
var_dump($mem->get('key2'));
// $stats = $mem->getExtendedStats();
// print_r($stats);
// $items=$mem->getExtendedStats ('items');
// $host = '10.14.85.166';
// $port = '11211';
// $items=$items["$host:$port"]['items'];
// foreach($items as $key=>$values){
// 	$number=$key;;
// 	$str=$mem->getExtendedStats ("cachedump",$number,0);
// 	$line=$str["$host:$port"];
// 	if( is_array($line) && count($line)>0){
// 		foreach($line as $key=>$value){
// 			echo $key.'=>';
// 			var_dump($mem->get($key));
// 			echo "\r\n";
// 		}
// 	}
// }
?>