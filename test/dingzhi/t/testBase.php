<?php
error_reporting(E_ALL ^ E_NOTICE);
require(realpath(dirname(__FILE__)).'/../../../modules/watcher/Base.class.php');
$sql = "select a.code,b.maxt from product_baseinfo a right join(
select aa.code,count(aa.itime) ci,max(aa.itime) maxt from product_info aa where itime > to_date('2013/11/26 16:30:00','yyyy/mm/dd hh24:mi:ss') group by aa.code 
) b on a.code = b.code
 where a.portal_channel is not null order by b.maxt desc";
new Base($sql);