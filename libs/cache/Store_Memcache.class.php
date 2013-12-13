<?php
/*
memcache操作类

作者:tonny<wodexintiao@gmail.com>
*/
class Store_Memcache{
	private static $mem;
	private static function getMemcache(){
		if(!isset(self::$mem)){
			self::$mem = new Memcache;
			self::$mem->addServer('10.14.85.167', 11211, true, 1, 1, 15, true); // retrt_interval=15
			self::$mem->addServer('10.14.85.166', 11211, true, 1, 1, 15, true); // retrt_interval=15
		}
		return self::$mem;
	}
	public static function getItem($key){
		$val = self::getMemcache()->get($key);
		return ( $val );
	}
	public static function setItem($key,$val,$indate=0){
		return self::getMemcache()->set($key,$val,MEMCACHE_COMPRESSED,$indate);
	}
}