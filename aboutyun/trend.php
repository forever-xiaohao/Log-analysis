<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','trend');
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
<body>
	<?php 
	require ROOT_PATH.'includes/header.inc.php';
	?>
	<div class="trend_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
<?php
  $_grahp_line_sql = "select PVNum,IPNum,TotalNum,hours from allstatistics where date=(select date from allstatistics group by date order by date desc limit 1) order by hours asc";
  $_result = _query($_grahp_line_sql);
  $_line_param = array();
  while ($_row=_fetch_array_list($_result)) {
    $_line_param['PVNum'][] = $_row['PVNum'];
    $_line_param['IPNum'][] = $_row['IPNum'];
    $_line_param['TotalNum'][] = $_row['TotalNum'];
  }
  $_PVNum = json_encode($_line_param['PVNum']);
  $_IPNum = json_encode($_line_param['IPNum']);
  $_TotalNum = json_encode($_line_param['TotalNum']);
?>
		<div class="trend_cent_top">
      <p>流量分析-趋势分析</p>
    </div>
    <?php
     require ROOT_PATH.'includes/total.inc.php';
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
            'echarts/chart/line'

		   ],
            function(ec){
               //--折线图--
               // 基于准备好的dom，初始化echarts图表
                var myChartline_2 = ec.init(document.getElementById('trend_line')); 
                
               var optionline_2 = {

                   tooltip : {
                       trigger: 'axis'
                   },
                   legend: {
                       data:['浏览次数（PV）','IP数','站内访问总量']
                   },

                   toolbox: {
                       show : true,
                       feature : {
                           mark : {show: true},
                           dataView : {show: true, readOnly: false},
                           magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                           restore : {show: true},
                           saveAsImage : {show: true}
                       }
                   },
                   calculable : true,
                   xAxis : [
                       {
                           type : 'category',
                           boundaryGap : false,
                           data : ['00:00-00:59','01:00-01:59','02:00-02:59','03:00-03:59','04:00-04:59','05:00-05:59','06:00-06:59','07:00-07:59','08:00-08:59','09:00-09:59','10:00-10:59','11:00-11:59','12:00-12:59','13:00-13:59','14:00-14:59','15:00-15:59','16:00-16:59','17:00-17:59','18:00-18:59','19:00-19:59','20:00-20:59','21:00-21:59','22:00-22:59','23:00-23:59']
                       }
                   ],
                   yAxis : [
                       {
                           type : 'value'
                       }
                   ],
                   series : [
                       {
                           name:'浏览次数（PV）',
                           type:'line',
                           // stack: '总量',
                           data:<?php echo $_PVNum;?>
                       },
                       {
                           name:'IP数',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_IPNum;?>
                       },
                       {
                           name:'站内访问总量',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_TotalNum;?>
                       }
                   ]
               };
               // 为echarts对象加载数据 
                myChartline_2.setOption(optionline_2); 
               
            }
      );

</script>
		
<div class="module_cent">
    <img src="image/yy.png"><p>温馨提示：流量趋势分析主要是统计网站每天各时段访问情况。下面折线图统计的参数有PV数、IP数、站内总访问量。（单位：/小时）</p>
  </div>
		<div id="trend_line">
			
		</div><!-- 图表结束 -->
<?php
$_show_info = "select PVNum,IPNum,TotalNum,hours from allstatistics where date=(select date from allstatistics group by date order by date desc limit 1) order by hours";
$_show_query=_query($_show_info);
$_show_detail=array();

?>
	<div class="module_cent">
    <img src="image/yy.png"><p>温馨提示：下表列出了网站每天各时段的详细参数情况。与上面折线图对应。</p>
  </div>
		<div class="trend_w"><p class="bb_w">查看:</p><p class="bb_t">统计报表</p></div>
		
		<div class="tr_table">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_time"><p>时段</p></td>
					<td class="tr_num"><p>浏览次数（PV）</p></td>
					<td class="tr_num"><p>独立访客（UV）</p></td>
					<td class="tr_num"><p>IP数</p></td>
					<td class="tr_num_1"><p>访问次数</p></td>
				</tr>
				<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
					<td class="tr_times"><p>全站统计</p></td>
					<td class="tr_nums"><p><?php echo $_result_total['TotalPV'];?></p></td>
					<td class="tr_nums"><p>----</p></td>
					<td class="tr_nums"><p><?php echo $_result_total['TotalIP'];?></p></td>
					<td class="tr_nums_1"><p><?php echo $_result_total['TotalPeople'];?></p></td>
				</tr>
<?php while (!!$_show_result=_fetch_array_list($_show_query)) {?>
        <tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
          <td class="tr_times_2"><p><?php echo _time($_show_result['hours']);?></p></td>
          <td class="tr_nums_2"><p><?php echo $_show_result['PVNum'];?></p></td>
          <td class="tr_nums_2"><p>----</p></td>
          <td class="tr_nums_2"><p><?php echo $_show_result['IPNum'];?></p></td>
          <td class="tr_nums_1_2"><p><?php echo $_show_result['TotalNum'];?></p></td>
        </tr>
    <?php }?>
			</table>
		</div>

	</div><!-- trend_center_end -->
	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>