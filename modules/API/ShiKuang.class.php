<?php
/*
实况表数据对外接口

作者:tonny<wodexintiao@gmail.com>
*/
require_once(realpath(dirname(__FILE__)).'/../../confs/cache.php');
require_once(realpath(dirname(__FILE__)).'/../../libs/DB.class.php');
require_once(realpath(dirname(__FILE__)).'/../../libs/cache/Store_Memcache.class.php');
require_once(realpath(dirname(__FILE__)).'/../watcher/ShiKuang.class.php');
class API_ShiKuang{
	private static $db;
	/*得到数据连接对象*/
	private static function getDB(){
		if(!isset(self::$db)){
			$conn = new Conn();
			self::$db = $conn->db;
		}
		return self::$db;
	}
	public static function getSKByAreaid($areaid){
		$cache_name = CACHE_TYPE_DATA.CACHE_KEY_SHIKUANG.'_'.$areaid;
		$cache_data = Store_Memcache::getItem($cache_name);
		if($cache_data === false){
			DB::select(ShiKuang::$SQL_INIT." where a.areaid = '$areaid'");
		}
		return $cache_data;
	}
}