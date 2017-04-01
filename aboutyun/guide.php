<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','guide');
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
	<script type="text/javascript" src="js/tab.js"></script>
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
	<div class="guide_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>受访分析-热点导航</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>

<?php
$_blog_sql="select sum(visitnum) as blog from blogmodel where time =(select time from blogmodel group by time order by time desc limit 1)";
$_group_sql="select sum(visitnum) as gp from groupmodel where time =(select time from groupmodel group by time order by time desc limit 1)";
$_guide_sql="select sum(visitnum) as guide from guidemodel where time =(select time from guidemodel group by time order by time desc limit 1)";
$_radio_sql="select sum(visitnum) as radio from radiomodel where time =(select time from radiomodel group by time order by time desc limit 1)";
$_ranklist_sql="select sum(visitnum) as ranklist from ranklistmodel where time =(select time from ranklistmodel group by time order by time desc limit 1)";
$_read_sql="select sum(visitnum) as rds from readmodel where time =(select time from readmodel group by time order by time desc limit 1)";
$_share_sql="select sum(visitnum) as share from sharemodel where time =(select time from sharemodel group by time order by time desc limit 1)";
$_weixin_sql="select sum(visitnum) as wx from weixinmodel where time =(select time from weixinmodel group by time order by time desc limit 1)";
$_read=_fetch_array($_read_sql);
$_guide=_fetch_array($_guide_sql);
$_blog=_fetch_array($_blog_sql);
$_ranklist=_fetch_array($_ranklist_sql);
$_share=_fetch_array($_share_sql);
$_group=_fetch_array($_group_sql);
$_radio=_fetch_array($_radio_sql);
$_weixin=_fetch_array($_weixin_sql);
$_arr_module=array();
for ($i=0; $i < 8; $i++) { 
	if($i==0){
		$_arr_module[$i]=$_guide['guide'];
	}
	if($i==1){
		$_arr_module[$i]=$_blog['blog'];
	}
	if($i==2){
		$_arr_module[$i]=$_read['rds'];
	}
	if($i==3){
		$_arr_module[$i]=$_weixin['wx'];
	}
	if($i==4){
		$_arr_module[$i]=$_ranklist['ranklist'];
	}
	if($i==5){
		$_arr_module[$i]=$_share['share'];
	}
	if($i==6){
		$_arr_module[$i]=$_group['gp'];
	}
	if($i==7){
		$_arr_module[$i]=$_radio['radio'];
	}
}
$_reault_bar = json_encode($_arr_module);
?>

<script src="chartjs/echarts.js"></script>

	   <script type="text/javascript">
	   	 require.config({
        paths: {
            echarts: './chartjs'
        }
    });
    require(
           [ 
            'echarts',
            'echarts/chart/bar',//按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
            'echarts/chart/line',
            'echarts/chart/funnel'
		   ],
           function(ec){
               var myChartbar = ec.init(document.getElementById('guide_bar'));
            myChartbar.setOption({
					    title: {
					        text: '论坛导航栏目访问量统计',
					        subtext: '大数据www.aboutyun.com',
					        x:'center'
					       // sublink: 'http://e.weibo.com/1341556070/AjQH99che'
					    },
					    tooltip : {
					        trigger: 'axis',
					        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
					        },
					        formatter: function (params) {
					            var tar = params[0];
					            return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
					        }
					    },
					    toolbox: {
					        show : true,
					        feature : {
					            mark : {show: true},
					            dataView : {show: true, readOnly: false},
					            restore : {show: true},
					            saveAsImage : {show: true}
					        }
					    },
					    xAxis : [
					        {
					            type : 'category',
					            splitLine: {show:false},
					            data :['导读','博客','图文阅读','微信点击','排行榜','资源分享','群组','广播']
					        }
					    ],
					    yAxis : [
					        {
					            type : 'value'
					        }
					    ],
					    series : [
					        {
					            name:'辅助',
					            type:'bar',
					            stack: '总量',
					            itemStyle:{
					                normal:{
					                    barBorderColor:'rgba(0,0,0,0)',
					                    color:'rgba(0,0,0,0)'
					                },
					                emphasis:{
					                    barBorderColor:'rgba(0,0,0,0)',
					                    color:'rgba(0,0,0,0)'
					                }
					            },
					            data:[0, 0, 0, 0, 0, 0,0,0,0,0]
					        },
					        {
					            name:'点击量',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'inside'}}},
					            data:<?php echo $_reault_bar;?>
					        }
					    ]
					});				         
            }
      );

	   </script> 
		<div class="module_cent">
				<img src="image/yy.png"><p>温馨提示：柱形图统计的是论坛各导航的点击量情况。</p>
		</div>
	<div id="guide_bar"></div><!-- 柱形图 -->

	<div class="module_cent">
		<img src="image/yy.png"><p>温馨提示：下面表格中展示了论坛导航的各参数统计详情。（按点击量排序）</p>
	</div>
<!-- 详细情况 -->
	<div class="menu1box">
		<ul id="menu1">
		   <li class="hover" onmouseover="setTab(1,0)"><a href="#">导读</a></li>
		   <li onmouseover="setTab(1,1)"><a href="#">博客</a></li>
		   <li onmouseover="setTab(1,2)"><a href="#">图文阅读</a></li>
		   <li onmouseover="setTab(1,3)"><a href="#">微信点击</a></li>
		   <li onmouseover="setTab(1,4)"><a href="#">排行榜</a></li>
		   <li onmouseover="setTab(1,5)"><a href="#">资源分享</a></li>
		   <li onmouseover="setTab(1,6)"><a href="#">群组</a></li>
		   <li onmouseover="setTab(1,7)"><a href="#">广播</a></li>
		</ul>
	</div>

	<div class="main1box">
		<div class="main" id="main1">
			<?php 
			$_detail_guide_sql ="select ipaddress,visitnum,time from guidemodel where time =(select time from guidemodel group by time order by time desc limit 1) order by visitnum desc limit 30";
			$_Num_guide_sql="select sum(visitnum) as TotalNum from guidemodel where time =(select time from guidemodel group by time order by time desc limit 1) group by time";
			$_Total_guide=_fetch_array($_Num_guide_sql);
			$_guide_query=_query($_detail_guide_sql);
			?>
			<ul class="block">
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_guide=_fetch_array_list($_guide_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_guide['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_guide['visitnum']?></p></td>
			      <td class="tr_nums_2"><p><?php echo round($_row_guide['visitnum']/$_Total_guide['TotalNum']*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_guide['time'];?></p></td>
			        </tr>
			        <?php }?>
				</table>
				<!-- <?php
				//_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
				_paging(2);
				?> -->
			</ul>
			<?php 
			$_detail_blog_sql ="select ipaddress,visitnum,time from blogmodel where time =(select time from blogmodel group by time order by time desc limit 1) order by visitnum desc limit 30";
			$_Num_blog_sql="select sum(visitnum) as TotalNum from blogmodel where time =(select time from blogmodel group by time order by time desc limit 1) group by time";
			$_Total_blog=_fetch_array($_Num_blog_sql);
			$_blog_query=_query($_detail_blog_sql);
			?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_blog=_fetch_array_list($_blog_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_blog['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_blog['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round($_row_blog['visitnum']/$_Total_blog['TotalNum']*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_blog['time']?></p></td>
			        </tr>
			        <?php }?>
				</table>
			</ul>
			<?php 
			$_detail_read_sql ="select ipaddress,visitnum,time from readmodel where time =(select time from readmodel group by time order by time desc limit 1) order by visitnum desc limit 30";
			$_Num_read_sql="select sum(visitnum) as TotalNum from readmodel where time =(select time from readmodel group by time order by time desc limit 1) group by time";
			$_Total_read=_fetch_array($_Num_read_sql);
			$_read_query=_query($_detail_read_sql);
			?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_read=_fetch_array_list($_read_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_read['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_read['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round($_row_read['visitnum']/$_Total_read['TotalNum']*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_read['time']?></p></td>
			        </tr>
			        <?php }?>
				</table>
			</ul>
			<?php 
				$_detail_weixin_sql ="select ipaddress,visitnum,time from weixinmodel where time =(select time from weixinmodel group by time order by time desc limit 1) order by visitnum desc limit 30";
				$_Num_weixin_sql="select sum(visitnum) as TotalNum from weixinmodel where time =(select time from weixinmodel group by time order by time desc limit 1) group by time";
				$_Total_weixin=_fetch_array($_Num_weixin_sql);
				$_weixin_query=_query($_detail_weixin_sql);
				?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_weixin=_fetch_array_list($_weixin_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_weixin['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_weixin['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round($_row_weixin['visitnum']/$_Total_weixin['TotalNum']*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_weixin['time']?></p></td>
			        </tr>
			         <?php }?>
				</table>
			</ul>
			<?php 
				$_detail_ranklist_sql ="select ipaddress,visitnum,time from ranklistmodel where time =(select time from ranklistmodel group by time order by time desc limit 1) order by visitnum desc limit 30";
				$_Num_ranklist_sql="select sum(visitnum) as TotalNum from ranklistmodel where time =(select time from ranklistmodel group by time order by time desc limit 1) group by time";
				$_Total_ranklist=_fetch_array($_Num_ranklist_sql);
				$_ranklist_query=_query($_detail_ranklist_sql);
				?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_ranklist=_fetch_array_list($_ranklist_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_ranklist['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_ranklist['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round($_row_ranklist['visitnum']/$_Total_ranklist['TotalNum']*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_ranklist['time']?></p></td>
			        </tr>
			         <?php }?>
				</table>
			</ul>
			<?php 
				$_detail_share_sql ="select ipaddress,visitnum,time from sharemodel where time =(select time from sharemodel group by time order by time desc limit 1) order by visitnum desc limit 30";
				$_Num_share_sql="select sum(visitnum) as TotalNum from sharemodel where time =(select time from sharemodel group by time order by time desc limit 1) group by time";
				$_Total_share=_fetch_array($_Num_share_sql);
				$_share_query=_query($_detail_share_sql);
				?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_share=_fetch_array_list($_share_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_share['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_share['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round(($_row_share['visitnum']/$_Total_share['TotalNum'])*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_share['time']?></p></td>
			        </tr>
			         <?php }?>
				</table>
			</ul>
			<?php 
				$_detail_group_sql ="select ipaddress,visitnum,time from groupmodel where time =(select time from groupmodel group by time order by time desc limit 1) order by visitnum desc limit 30";
				$_Num_group_sql="select sum(visitnum) as TotalNum from groupmodel where time =(select time from groupmodel group by time order by time desc limit 1) group by time";
				$_Total_group=_fetch_array($_Num_group_sql);
				$_group_query=_query($_detail_group_sql);
				?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					</tr>
					<?php
					while (!!$_row_group=_fetch_array_list($_group_query)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_group['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_group['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round(($_row_group['visitnum']/$_Total_group['TotalNum'])*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_group['time']?></p></td>
			        </tr>
			         <?php }?>
				</table>
			</ul>
			<?php 
				$_detail_radio_sql ="select ipaddress,visitnum,time from radiomodel where time =(select time from radiomodel group by time order by time desc limit 1) order by visitnum desc limit 30";
				$_Num_radio_sql="select sum(visitnum) as TotalNum from radiomodel where time =(select time from radiomodel group by time order by time desc limit 1) group by time";
				$_Total_radio=_fetch_array($_Num_radio_sql);
				$_radio_result=_query($_detail_radio_sql);
				?>
			<ul>
				<table border="0"cellspacing="0" cellpadding="0">
					<tr>
						<td class="tr_num1"><p>访问IP</p></td>
						<td class="tr_num4"><p>访问次数</p></td>
						<td class="tr_num2"><p>所占%比</p></td>
						<td class="tr_num3"><p>访问时间</p></td>
					<?php
					while (!!$_row_radio=_fetch_array_list($_radio_result)) {
					?>
					<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
			          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_radio['ipaddress']?></a></p></td>
			          <td class="tr_nums_4"><p><?php echo $_row_radio['visitnum']?></p></td>
			          <td class="tr_nums_2"><p><?php echo round(($_row_radio['visitnum']/$_Total_radio['TotalNum'])*100,2).'%';?></p></td>
			          <td class="tr_nums_3"><p><?php echo $_row_radio['time']?></p></td>
			        </tr>
			         <?php }?>
				</table>
			</ul>
		</div>
	</div>




	</div><!-- guide_center_end -->

	


	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>