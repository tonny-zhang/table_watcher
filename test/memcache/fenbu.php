<?php
$mcd = new Memcached();
// Consistent hashing
$mcd->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
$mcd->setOption(Memcached::OPT_HASH, Memcached::HASH_DEFAULT);
$servers = array
(
	array('10.4.85.166', 11211),
	array('10.4.85.167', 11211),
);
$mcd->addServers($servers);
$end = 10000;
for ($i = 0; $i < $end; $i++)
{
	$mcd->add($i, $i, 3600);
}
// $mcd->addServer('192.168.1.225', 11213);
// $mcd->addServer('192.168.1.225', 11214);
for ($i = 0; $i < $end; $i++)
{
	$mcd->get($i);
}
$stats = $mcd->getStats();
foreach ($stats as $k => $v)
{
	echo $k, '->items=', $v['total_items'], '
';
}
$mcd->close();