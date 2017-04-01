<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','detail');

//引入公共文件
//转换成硬路径引入速度更快
require dirname(__FILE__).'/includes/common.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>About云WEB日志分析</title>
	<?php
	require ROOT_PATH.'includes/link.inc.php';
	?>
	<script language="javascript">
   function mouseOver(obj){
      if(obj.className="tb_row")
         obj.className="tb_color";
   }
   function mouseOut(obj){
      if(obj.className="tb_color")
         obj.className="tb_row";
   }
</script> 
</head>
<body>
	<?php 
	require ROOT_PATH.'includes/header.inc.php';
	?>

	<div class="detail_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>

<?php
global $_pagesize,$_pagenum;
//第一个参数是获取总条数，第二个参数是每页多少条
_page("select ipaddress from v_accessdetail",40);

//访问详情
$_detail_sql = "select accresstime,fromurl,comeurl,ipaddress,region,city
from v_accessdetail limit $_pagenum,$_pagesize";
$_result = _query($_detail_sql);

?>



		<div class="detail_cent_top"><p>流量分析-访问明细</p></div><!-- detail_cent_right_end -->
		<div class="module_cent">
			<img src="image/yy.png"><p>温馨提示：下表罗列出来自于分页面链接的访问详情。</p>
		</div>
		<div class="trend_w"><p class="bb_w">查看:</p><p class="bb_t">日志详情</p></div>

		<div class="detail_table">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_time"><p>浏览时间</p></td>
					<td class="tr_num1"><p>页面来源</p></td>
					<td class="tr_num1"><p>受访</p></td>
					<td class="tr_num2"><p>IP</p></td>
					<td class="tr_num3"><p>地区</p></td>
				</tr>
	<?php 
		while(!!$_row = _fetch_array_list($_result)){

	?>
				<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
		          <td class="tr_times"><p>><?php echo _datetime($_row['accresstime']);?></p></td>
		          <td class="tr_nums_1"><p><a href="<?php echo $_row['fromurl'];?>"><?php echo _url_judge(_url_len($_row['fromurl'],35));?></a></p></td>
		          <td class="tr_nums_1"><p><a href="<?php echo $_row['comeurl'];?>"><?php echo _url_judge(_url_len($_row['comeurl'],35));?></a></p></td>
		          <td class="tr_nums_2"><p><?php echo $_row['ipaddress'];?></p></td>
		          <td class="tr_nums_3"><p><?php echo $_row['region'].'-'.$_row['city'];?></p></td>
		        </tr>

<?php }
?>

		        
			</table>





<?php
//_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
_paging(2);
?>


		</div>
	</div><!-- detail_center_end -->

	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>