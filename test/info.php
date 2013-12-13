<?php
var_dump('a1' < 'a2');
$starttime = microtime(true);
$endtime = microtime(true);
echo '['.date('Y-m-d H:i:s').'] '.($endtime - $starttime).'
';
$endtime = microtime(true);
echo '['.date('Y-m-d H:i:s').'] '.($endtime - $starttime).'
';
// phpinfo();