<?php
//防止恶意调用
if(!defined('IN_TG')){
  echo 'ACCESS DEFINED!';
  exit();
}

header("Content-type: text/html; charset=utf-8");
/**
*_connectDB()连接MYSQL数据库
*@access public
*@return void
*/
function _connectDB(){
	global $_conn;//定义全局变量，意图是将此变量在函数外部也可以访问
	//创建数据库连接
	if (!$_conn = @mysql_connect(DB_HOST,DB_USER,DB_PWD)) {
		exit("数据库连接失败！");
	} 
}

/**
*_select_db()选择一款数据库
*@access public
*@return void
**/
function _select_db(){
	//选择一款数据库
	if(!mysql_select_db(DB_NAME)){
		exit("找不到指定的数据库！");
	}
}

/**
*_set_names()设置字符集
*@access public
*@return void
**/
function _set_names(){
	//选择字符集
	if(!mysql_query('SET NAMES UTF8')){
		exit("字符集错误!");
	}
}

/**
*_query()执行SQL语句函数
*@access public
*@param $_sql 要执行的SQL语句
*@return $_result 返回执行的结果
*/
function _query($_sql){
	if(!$_result = mysql_query($_sql)){
		exit("SQL执行失败！".mysql_error());
	}
	return $_result;
}

/**
*_fetch_array()只能获取指定数据集的一条数据组
*@access public
*@param $_sql 要执行的SQL语句
*@return 返回执行的结果
*/
function _fetch_array($_sql){
	return mysql_fetch_array(_query($_sql),MYSQL_ASSOC);
}

/**
*_fetch_array_list()可以返回指定数据集的所有数据组
*@access public
*@param $_sql 要执行的SQL语句
*@return 返回执行的结果
*/
function _fetch_array_list($_result){
	return mysql_fetch_array($_result,MYSQL_ASSOC);
}
/**
*该函数获得数据库中的数据量
*
*/
function _num_rows($_result){
	return mysql_num_rows($_result);
}
/**
*_close()关闭数据库函数
*/

function _close(){
	mysql_close();
}


?>