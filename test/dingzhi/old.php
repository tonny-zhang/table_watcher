<?php
error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);
$starttime = microtime(true);
require(realpath(dirname(__FILE__)).'/../../modules/watcher/ShiKuang.class.php');
$c = new Conn();
$idarray=file("/home/product/zhangyanwei/flash_xml/modules/www_program/idconfig.txt");
$file_fc_img="/home/product/zhangyanwei/flash_xml/modules/dingzhi_niuy/";

$sql="select * from observe.ele_awst_public
 where (v01000, c_bjtime) in (select v01000, max(c_bjtime)
                                 from observe.ele_awst_public
                                group by v01000)";

$rs = $c->db->Execute($sql);
$endtime = microtime(true);
//输出执行时间到日志文件
echo '['.date('Y-m-d H:i:s').'] '.($endtime - $starttime).'
';
	$count = $rs->RecordCount();
	if($count != 0)
	{
		for($j = 0; $j < $count; $j++)//$count
		{
			$table = $rs->FetchRow();
			//print_r($table);
			$sta = $table['V01000'];
			for($k=0;$k<count($idarray);$k++)
			{
				$id_line_Array=explode(",", $idarray[$k]);
				if($id_line_Array[0]==$sta)
				{
					$areaid=trim($id_line_Array[1]);
					$content=json_decode(file_get_contents($file_fc_img.$areaid.".html"));
					$img1=$content->{'weatherinfo'}->{'img1'};
					$img1=str_replace(".gif", "", $img1);
					$weather=$content->{'weatherinfo'}->{'weather'};
					$thisstring='{"weather":"'.$weather.'","img":"'.$img1.'","temp":"'.$table['V12001'].'"}';
					$fp_wr = fopen("/home/product/zhangyanwei/flash_xml/modules/www_data_post/dingzhi/".$areaid.".html","w");
					fwrite($fp_wr,$thisstring);
					fclose($fp_wr);
				}
			}
		}
	}
 
?>