<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','visitpage');

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
</head>
<body>
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
	<?php 
	require ROOT_PATH.'includes/header.inc.php';
	?>

	<div class="visitpage_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>受访分析-受访页面</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>
	<div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：以下表格显示的是受访页面的详细情况统计！</p>
    </div>
	    <div class="trend_w">
				<p class="bb_w">查看:</p>
				<p class="bb_t">受访页面</p>
		</div>


<?php
global $_pagesize,$_pagenum;
//第一个参数是获取总条数，第二个参数是每页多少条
_page("select VisitUrl,sum(VisitNum) as T_visitnum,sum(IPNum) as T_ipnum,sum(TotalNum) as T_totalnum from visitpagestatistics where date = (select date from visitpagestatistics  group by date order by date limit 1) group by VisitUrl",30);

$_visitpage_sql = "select VisitUrl,sum(VisitNum) as T_visitnum,sum(IPNum) as T_ipnum,sum(TotalNum) as T_totalnum from visitpagestatistics where date = (select date from visitpagestatistics  group by date order by date limit 1) group by VisitUrl order by T_totalnum desc limit $_pagenum,$_pagesize";
$_result = _query($_visitpage_sql);
?>

		<div class="visitpage_detail">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_num1"><p>受访链接</p></td>
					<td class="tr_num4"><p>访问次数</p></td>
					<td class="tr_num2"><p>IP数</p></td>
					<td class="tr_num3"><p>站内总访问次数</p></td>
				</tr>
<?php while(!!$_row = _fetch_array_list($_result)) {?>
				<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
		          <td class="tr_nums_1"><p><a href="<?php echo $_row['VisitUrl'];?>">><?php echo _url_len($_row['VisitUrl'],60);?></a></p></td>
		          <td class="tr_nums_4"><p><?php echo $_row['T_visitnum']?></p></td>
		          <td class="tr_nums_2"><p><?php echo $_row['T_ipnum']?></p></td>
		          <td class="tr_nums_3"><p><?php echo $_row['T_totalnum']?></p></td>
		        </tr>
<?php }?>
		</table>

<?php
//_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
_paging(2);
?>
		</div><!-- visitpage_detail_end -->

	</div><!-- visitpage_center_end -->



	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
	
</body>
</html>