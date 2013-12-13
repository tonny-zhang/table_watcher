<?php
set_time_limit(0);
$starttime = microtime(true);

require_once('../libs/connection.php');
$c = new Conn();
/*在185.198上测试结果为平均大概10s左右*/
$sql="select a.code,b.maxt from product_baseinfo a right join(
select aa.code,count(aa.itime) ci,max(aa.itime) maxt from product_info aa where itime > to_date('2013/11/26 16:30:00','yyyy/mm/dd hh24:mi:ss') group by aa.code 
) b on a.code = b.code
 where a.portal_channel is not null order by b.maxt desc";
$sql = 'select a.stationid,a.areaid,b.rowid,b.v01000,b.c_bjtime,b.v12001,b.v11043,b.v11041,b.v13003,b.v13019,b.v13021,b.v13023 from dict_station a join observe.ele_awst_public b on a.stationid = b.v01000';
$sql="select * from observe.ele_awst_public
 where (v01000, c_bjtime) in (select v01000, max(c_bjtime)
                                 from observe.ele_awst_public
                                group by v01000)";
$rs = $c->db->Execute($sql);var_dump($rs);
$count = $rs->RecordCount();
if($count > 0){
        /*此处理也会消耗时间,去掉这个时间大概在6s*/
        // for($j = 0; $j < $count; $j++){
        //         $table = $rs->FetchRow();
        //         var_dump($table);
        // }
}else{
        echo 'no data';
}

$endtime = microtime(true);
echo 'spend '.($endtime-$starttime).'s';
?>