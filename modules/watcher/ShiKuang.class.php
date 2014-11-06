<?php
require_once(realpath(dirname(__FILE__)).'/Base.class.php');
require_once(realpath(dirname(__FILE__)).'/../../confs/cache.php');
class ShiKuang extends Base implements iBase{
	static $TABLE_NAME = 'observe.ele_awst_public';
	static $CACHE_NAME = CACHE_KEY_SHIKUANG;
	public static $SQL_INIT = "select a.stationid,a.areaid,rowidtochar(b.rowid) rid,b.* from dict_station a join observe.ele_awst_public b on a.stationid = b.v01000 order by b.rowid";
	
	public function modifyedCache($rowid){
		echo 'have ShiKuang data modifyed';
	}
	public function noModifyed(){
		echo 'no modifyed';
	}
	public static function heartbeat(){
		new self(array(
			'table_name'=> self::$TABLE_NAME,
			'cache_name'=> self::$CACHE_NAME,
			'sql_init'=> self::$SQL_INIT,
			'data_key'=> 'areaid',
		));
	}
	public function getModifySQL($prev_rowid){
		if($prev_rowid){
			return $SQL_INIT." and b.rowid > '$prev_rowid'";
		}
		return false;
	}
}