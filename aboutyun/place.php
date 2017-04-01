<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','place');

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

	<div class="place_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>访客分析-地区/运营商</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>

<?php
	$_ip_map_sql ="select region,count(ipaddress) as ipnum,sum(visitNum) as TotalVisitNum from ipdetail where time = (select time from ipdetail group by time order by time desc limit 1 ) group by region order by TotalVisitNum desc";
	$_map_result = _query($_ip_map_sql);
	$_arr_map=array();
	$_map_max_data = array();
	$i=0;
	while (!!$_row_map=_fetch_array_list($_map_result)) {
		$_arr_map[$i]['name']=_privience($_row_map['region']);
		$_arr_map[$i]['value']=$_row_map['TotalVisitNum'];
		if($i==0){
			$_map_max_data['max']=(int)$_row_map['TotalVisitNum'];
		}
		$i++;
	}
	$_pri_data=json_encode($_arr_map);

	$_ip_bar_sql = "select isp ,sum(visitNum) as Sumvisit,time from ipdetail where time = (select time from ipdetail group by time order by time desc limit 1) group by isp order by Sumvisit asc limit 6";
	$_ip_bar_result = _query($_ip_bar_sql);
	$_arr_bar = array();
	while (!!$_row_bar=_fetch_array_list($_ip_bar_result)) {
		$_arr_bar['isp'][]=$_row_bar['isp'];
		$_arr_bar['visitNum'][]=$_row_bar['Sumvisit'];
		$_arr_bar['time']=$_row_bar['time'];
	}
	$_data_isp=json_encode($_arr_bar['isp']);
	//echo $_data_isp;
	$_data_visitnum=json_encode($_arr_bar['visitNum']);
	//echo $_data_visitnum;
	//$_data_time=json_encode($_arr_bar['time']);
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
            'echarts/chart/map',//按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
            'echarts/chart/line',
            'echarts/chart/funnel',
            'echarts/chart/bar',
            'echarts/chart/pie'



		   ],
            function(ec){
               // 为echarts对象加载数据 
               // --- 地图 ---
            var myChart1 = ec.init(document.getElementById('place_map'));
            myChart1.setOption({
					    title : {
					        text: '网站用户分布图',
					        subtext: 'www.aboutyun.com',
					        x:'left'
					    },
					    tooltip : {
					        trigger: 'item'
					    },
					    legend: {
					        orient: 'vertical',
					        x:'center',
					        data:['访问量统计']
					    },
					    dataRange: {
					        min: 0,
					        max: <?php echo $_map_max_data['max'];?>,
					        x: 'left',
					        y: 'bottom',
					        text:['高','低'],           // 文本，默认为数值文本
					        calculable : true
					    },
					    toolbox: {
					        show: true,
					        orient : 'vertical',
					        x: 'right',
					        y: 'center',
					        feature : {
					            mark : {show: true},
					            dataView : {show: true, readOnly: false},
					            restore : {show: true},
					            saveAsImage : {show: true}
					        }
					    },
					    roamController: {
					        show: true,
					        x: 'right',
					        mapTypeControl: {
					            'china': true
					        }
					    },
					    series : [
		
					        {
					            name: '访问量统计',
					            type: 'map',
					            mapType: 'china',
					            itemStyle:{
					                normal:{label:{show:true}},
					                emphasis:{label:{show:true}}
					            },
					            data:<?php echo $_pri_data;?>
					        }
					    ]
					});

			var myChart2 = ec.init(document.getElementById('place_pie'));
            myChart2.setOption({
					    title: {
					        text: '论坛当天网络运营商情况统计',
					        subtext: 'www.aboutyun.com'
					    },
					    tooltip: {
					        trigger: 'axis',
					        axisPointer: {
					            type: 'shadow'
					        }
					    },
					   
					    grid: {
					        left: '3%',
					        right: '4%',
					        bottom: '3%',
					        containLabel: true
					    },
					    xAxis: {
					        type: 'value',
					        boundaryGap: [0, 0.01]
					    },
					    yAxis: {
					        type: 'category',
					        data: <?php echo $_data_isp;?>
					    },
					    series: [
					        {
					            name: '当天用户总数',
					            type: 'bar',
					            data:<?php echo $_data_visitnum;?>
					        }
					    ]
					});




	}
);
	   </script> 
	    <div class="graph_all">
	    	<div class="module_cent">
				<img src="image/yy.png"><p>温馨提示：下面统计的是网站一天之内用户分布图。（颜色越深说明来自于该地区的访客人数更多）</p>
			</div>

	    	<div id="place_map"></div>

			<div class="module_cent">
				<img src="image/yy.png"><p>温馨提示：柱形图统计的是访客运营商的情况。（主要统计前6名的运营商总量）</p>
			</div>

	    	<div id="place_pie"></div>

	    </div><!-- 图表外围 -->

	    <div class="module_cent">
				<img src="image/yy.png"><p>温馨提示：下表统计显示访客所在地的详细信息，以及访客所使用的运营商的情况。</p>
		</div>

<!-- 详细情况 -->
	<div class="menu1box">
		<ul id="menu1">
		   <li class="hover" onmouseover="setTab(1,0)"><a href="#">按省份统计</a></li>
		   <li onmouseover="setTab(1,1)"><a href="#">按市统计</a></li>
		   <li onmouseover="setTab(1,2)"><a href="#">按运营商统计</a></li>
		</ul>
	</div>

<?php
$_pri_sql = "select region,sum(visitNum) as T_Visit,count(ipaddress) as ipnum,time from ipdetail where time =(select time from ipdetail group by time order by time desc limit 1) group by region order by T_Visit desc";
$_pri_result = _query($_pri_sql);

?>

	<div class="main1box">
			<div class="main" id="main1">
				<ul class="block">
					<table border="0"cellspacing="0" cellpadding="0">
						<tr>
							<td class="tr_num1"><p>所在省份</p></td>
							<td class="tr_num4"><p>访问总次数</p></td>
							<td class="tr_num2"><p>IP数</p></td>
							<td class="tr_num3"><p>访问时间</p></td>
						</tr>
						<?php while (!!$_row_pri = _fetch_array_list($_pri_result)) {?>
						<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
				          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_pri['region']?></a></p></td>
				          <td class="tr_nums_4"><p><?php echo $_row_pri['T_Visit'];?></p></td>
				      	  <td class="tr_nums_2"><p><?php echo $_row_pri['ipnum'];?></p></td>
				          <td class="tr_nums_3"><p><?php echo $_row_pri['time'];?></p></td>
				        </tr>
				        <?php }?>
					</table>
				</ul>
				<?php
$_city_sql = "select city,sum(visitNum) as T_Visit,count(ipaddress) as ipnum,time from ipdetail where time =(select time from ipdetail group by time order by time desc limit 1) group by city order by T_Visit desc";
$_city_result = _query($_city_sql);

?>
				<ul>
					<table border="0"cellspacing="0" cellpadding="0">
						<tr>
							<td class="tr_num1"><p>所在地区</p></td>
							<td class="tr_num4"><p>访问总次数</p></td>
							<td class="tr_num2"><p>IP数</p></td>
							<td class="tr_num3"><p>访问时间</p></td>
						</tr>
						<?php while (!!$_row_city = _fetch_array_list($_city_result)) {?>
						<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
				          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_city['city']?></a></p></td>
				          <td class="tr_nums_4"><p><?php echo $_row_city['T_Visit'];?></p></td>
				      	  <td class="tr_nums_2"><p><?php echo $_row_city['ipnum'];?></p></td>
				          <td class="tr_nums_3"><p><?php echo $_row_city['time'];?></p></td>
				        </tr>
				        <?php }?>
					</table>
				</ul>
<?php
$_isp_sql = "select isp,sum(visitNum) as T_Visit,count(ipaddress) as ipnum,time from ipdetail where time =(select time from ipdetail group by time order by time desc limit 1) group by isp order by T_Visit desc";
$_isp_result = _query($_isp_sql);

?>
				<ul>
					<table border="0"cellspacing="0" cellpadding="0">
						<tr>
							<td class="tr_num1"><p>运营商信息</p></td>
							<td class="tr_num4"><p>访问总次数</p></td>
							<td class="tr_num2"><p>IP数</p></td>
							<td class="tr_num3"><p>访问时间</p></td>
						</tr>
						<?php while (!!$_row_isp = _fetch_array_list($_isp_result)) {?>
						<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
				          <td class="tr_nums_1"><p><a href="#">><?php echo $_row_isp['isp']?></a></p></td>
				          <td class="tr_nums_4"><p><?php echo $_row_isp['T_Visit'];?></p></td>
				      	  <td class="tr_nums_2"><p><?php echo $_row_isp['ipnum'];?></p></td>
				          <td class="tr_nums_3"><p><?php echo $_row_isp['time'];?></p></td>
				        </tr>
				        <?php }?>
					</table>
				</ul>
			</div>
	</div>



	</div><!-- guide_center -->

	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>