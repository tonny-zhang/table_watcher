<?php
/*
操作数据库类（主要是select查询操作）

作者:tonny<wodexintiao@gmail.com>
*/
require_once(realpath(dirname(__FILE__)).'/adodb5/adodb.inc.php');
class DB{
	private static $db;
	/*得到数据连接对象*/
	private static function getDB(){
		if(!isset(self::$db)){
			$dsn = 'oci8://web_user:web_user123@portaldb?charset=UTF8'; 	
			self::$db = ADONewConnection($dsn);
		}
		return self::$db;
	}
	/*查询*/
	public static function select($sql){
		echo $sql;
		$ret = self::getDB()->Execute($sql);
		$count = $ret->RecordCount();
		$result = array();
		for($i=0;$i<$count;$i++){
			$data = $ret->GetRowAssoc(false);
			$result[] = $data;
			$ret->MoveNext();
		}
		return $result;
	}
}