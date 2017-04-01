<?php
	//防止恶意调用
	if(!defined('IN_TG')){
	  echo 'ACCESS DEFINED!';
	  exit();
	}
	//转换硬路径
	define('ROOT_PATH',substr(dirname(__FILE__),0,-8));
	//拒绝低版本
	if(PHP_VERSION < '4.1.0'){
		exit("Version is too low !");
	}

	//引入函数库
	require ROOT_PATH."includes/mysql.func.php";
	require ROOT_PATH."includes/global.func.php";

	//数据库连接
	define('DB_HOST', 'localhost');
	define('DB_USER','root');
	define('DB_PWD', '');
	define('DB_NAME', 'aboutyun');


	//初始化数据库
	_connectDB();//连接MYSQL数据库
	_select_db();//选择指定的数据库
	_set_names();//设置字符集


	// $_sql="select ipaddress,visitnum from sharemodel";
	//  //$row = _fetch_array($_sql);
	// //$_result = mysql_query($_sql);
	// //我们必须是每次重新读取结果集，而不是重新去执行SQL语句
	// $_result = _query($_sql);
	// while (!!$_row = mysql_fetch_array($_result,MYSQL_ASSOC)) {
	// 	echo $_row[0] ."-----". $_row[1];
	// 	echo "</br>";
	// }
?>