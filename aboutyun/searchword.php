<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','searchword');

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
	
	<div class="searchword_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>来源分析-搜索词统计</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>
	<div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：以下表格显示的是搜索词的详细情况，以及每一个搜索词所占的比例！</p>
    </div>
		<div class="trend_w">
				<p class="bb_w">查看:</p>
				<p class="bb_t">搜索词</p>
		</div>
	
<?php
global $_pagesize,$_pagenum;
//第一个参数是获取总条数，第二个参数是每页多少条
_page("select SearchWord,SearchNum from searchstatistics",30);

$_s_sql = "select SearchWord,SearchNum from searchstatistics where date = (select date from searchstatistics  group by date order by date limit 1)  order by SearchNum desc limit $_pagenum,$_pagesize";
$_s_total_sql = "select sum(SearchNum) as search_total from searchstatistics where date = (select date from searchstatistics  group by date order by date limit 1)";
$_result = _query($_s_sql);
$_se_data = _fetch_array($_s_total_sql);
?>


		<div class="searchword_detail">
			<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>序号</p></td>
						<td class="tr_num4"><p>搜索词</p></td>
						<td class="tr_num2"><p>搜索量</p></td>
						<td class="tr_num3"><p>占比</p></td>
					</tr>
					<?php 
					$i=0;
					while(!!$_row = _fetch_array_list($_result)) {
						$i++;
						?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
					<td class="tr_nums_4"><p><?php echo $i;?></p></td>
			          <td class="tr_nums_1"><p><a href="<?php echo $_row['SearchWord'];?>"><?php echo $_row['SearchWord'];?></a></p></td>
			          <td class="tr_nums_2"><p><?php echo $_row['SearchNum'];?></p></td>
			          <td class="tr_nums_3"><p><?php echo round($_row['SearchNum']/$_se_data['search_total']*100,2).'%';?></p></td>
			        </tr>
			        <?php }?>
			</table>
<?php
//_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
_paging(2);
?>
		</div><!-- fromurl_detail_end -->

	</div><!-- searchword_center_end -->

	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>