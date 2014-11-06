<?php
/*
监控表抽象类

作者:tonny<wodexintiao@gmail.com>
*/
error_reporting(E_ALL ^ E_NOTICE);
require_once(realpath(dirname(__FILE__)).'/../../confs/cache.php');
require_once(realpath(dirname(__FILE__)).'/../../libs/DB.class.php');
require_once(realpath(dirname(__FILE__)).'/../../libs/cache/Store_Memcache.class.php');
/*用接口定义必须实现静态heartbeat方法(Strict Standards下会提示错误)*/
interface iBase{
	/*
	子类固定写法
	public static function heartbeat(){
		new self(self::$CACHE_NAME,self::$SQL);
	}
	*/
	public static function heartbeat();
}
abstract class Base{
	var $cache_name_heartbeat;
	var $cache_name_data;
	var $cache_name_init;
	var $data_key;
	var $sql_heartbeat;//心跳sql
	var $table_name;
	var $false = false;
	public function __construct($options){
		if(!is_array($options) || !isset($options['table_name']) || !isset($options['cache_name']) || !isset($options['data_key'])){
			return $false;
		}
		$this->table_name = $options['table_name'];
		$this->cache_name_heartbeat = CACHE_TYPE_FLAG.$options['cache_name'];
		$this->cache_name_data = CACHE_TYPE_DATA.$options['cache_name'].'_';
		$this->cache_name_init = CACHE_TYPE_INIT.$options['cache_name'];
		$this->data_key = $options['data_key'];
		$this->sql_heartbeat = 'select max(rowid) mrid from '.$options['table_name'];//!!这里可以会丢失簇表rowid重复的数据
		$this->sql_init = $options['sql_init'];
		$this->_heartbeat();
	}
	/*心跳测试是否有新数据（用rowid粗略判断表更新） */
	// 这里暂时先忽略`alert table t move`等rowid修改的情况
	// 暂时忽略簇表导致的rowid重复问题
	private function _heartbeat(){
		//只需要初始化一次
		if(!Store_Memcache::getItem($this->cache_name_init)){
			return $this->_initCache();
		}
		$cache_name = $this->cache_name_heartbeat;
		$flag_incache = Store_Memcache::getItem($cache_name);

		$ret = DB::select($this->sql_heartbeat);
		if(count($ret) > 0 &&  $ret[0]['mrid'] !== $flag_incache){
			$result = $this->_modifyCache($this->getModifySQL($flag_incache));
			$this->modifyedCache($result);
		}else{
			$this->noModify();
		}	
	}
	private static function _getSql($sql){
		return 'select * from ('.$sql.') where rownum < '.MAX_DATA_NUM;
	}
	private function _initCache(){
			$this->_modifyCache($this->sql_init);
	}
	private function _modifyCache($sql){
		if(!$sql){
			return;
		}
		$sql = self::_getSql($sql);
		$ret = DB::select($sql);
		foreach ($ret as $data) {
			$result[] = $data;
			if(empty($data)){
				break;
			}
			if(!isset($max_rowid) || $max_rowid < $data['rid']){
				$max_rowid = $data['rid'];
			}
			$cache_name = $this->cache_name_data.$data[$this->data_key];
			echo $cache_name.'
			';
			Store_Memcache::setItem($cache_name,$data);
		}
		$cache_name_heartbeat = $this->cache_name_heartbeat;
		if(isset($max_rowid) && $max_rowid > Store_Memcache::getItem($cache_name_heartbeat)){
			Store_Memcache::setItem($cache_name_heartbeat,$max_rowid);
		}
		return $result;
	}
	abstract protected function getModifySQL($prev_rowid);
	/*监控表启动时初始化缓存数据(提高缓存命中率)*/
	// abstract protected function initCache();
	/*更新缓存数据逻辑*/
	abstract protected function modifyedCache($ret);
	/*当没有数据更新时调用*/
	abstract protected function noModifyed();
	
}

