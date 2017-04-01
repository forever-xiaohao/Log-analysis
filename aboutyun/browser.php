<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','browser');
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

<?php
$_pie_sql = "select userbrowser,PVNum from browserstatistics 
where date = (select date from browserstatistics  group by date order by date limit 1)
group by userbrowser order by PVNum desc limit 5";

$_pie_result = _query($_pie_sql);
$_arr_pie = array();
$_arr_browser= array();
$i=0;
while (!!$_pie_row = _fetch_array_list($_pie_result)) {
	$_arr_pie[$i]['value']=$_pie_row['PVNum'];
	$_arr_pie[$i]['name']=$_pie_row['userbrowser'];
	$i++;
	$_arr_browser['browser'][]=$_pie_row['userbrowser'];
}
$_pie_data1 = json_encode($_arr_pie);
$_pie_data2 = json_encode($_arr_browser['browser']);
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
            'echarts/chart/funnel',
            'echarts/chart/pie'

		   ],
            function(ec){
               // 为echarts对象加载数据 
                var myChartpie = ec.init(document.getElementById('browser_pie'));
                var optionpie = {
					    title : {
					        text: 'About云论坛访客终端详情统计',
					        subtext: '大数据www.aboutyun.com',
					        x:'center'
					    },
					    tooltip : {
					        trigger: 'item',
					        formatter: "{a} <br/>{b} : {c} ({d}%)"
					    },
					    legend: {
					        orient : 'vertical',
					        x : 'left',
					        data:<?php echo $_pie_data2;?>
					    },
					    toolbox: {
					        show : true,
					        feature : {
					            mark : {show: true},
					            dataView : {show: true, readOnly: false},
					            magicType : {
					                show: true, 
					                type: ['pie', 'funnel'],
					                option: {
					                    funnel: {
					                        x: '25%',
					                        width: '50%',
					                        funnelAlign: 'left',
					                        max: 1548
					                    }
					                }
					            },
					            restore : {show: true},
					            saveAsImage : {show: true}
					        }
					    },
					    calculable : true,
					    series : [
					        {
					            name:'访问来源',
					            type:'pie',
					            radius : '55%',
					            center: ['50%', '60%'],
					            data:<?php echo $_pie_data1;?>
					        }
					    ]
					};

               // 为echarts对象加载数据 
                myChartpie.setOption(optionpie); 
            }
      );

	   </script> 
</head>
<body>
	<?php 
	require ROOT_PATH.'includes/header.inc.php';
	?>
	<div class="browser_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>

		<div class="trend_cent_top">
	      <p>访客分析-终端详情</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>

	 	<div class="module_cent">
      		<img src="image/yy.png"><p>温馨提示：有关访客终端的统计主要以PV值作为参考依据，详情如下（终端PV值前5名）。</p>
    	</div>

    	<div id="browser_pie"></div><!-- fromurl_line_end -->

		<div class="module_cent">
      		<img src="image/yy.png"><p>温馨提示：如下显示的而是访客终端的详细参数统计，包括：终端名称、PV值、IP数、该终端总访问次数。</p>
    	</div>

    	<div class="trend_w">
			<p class="bb_w">查看:</p>
			<p class="bb_t">访客终端</p>
		</div>

<?php
	$_browser_sql = "select userbrowser,PVNum,IPNum,TotalNum from browserstatistics where date = (select date from browserstatistics  group by date order by date limit 1) group by userbrowser order by TotalNum desc limit 30";
	$_browser_result = _query($_browser_sql);
?>

		<div class="browser_detail">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_num1"><p>浏览器</p></td>
					<td class="tr_num4"><p>PV数</p></td>
					<td class="tr_num2"><p>IP数</p></td>
					<td class="tr_num3"><p>站内总访问次数</p></td>
				</tr>
				<?php while($_row_browser=_fetch_array_list($_browser_result)){?>
				<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
		          <td class="tr_nums_1"><p><a href="<?php echo $_row_browser['userbrowser']?>">><?php echo $_row_browser['userbrowser']?></a></p></td>
		          <td class="tr_nums_4"><p><?php echo $_row_browser['PVNum']?></p></td>
		          <td class="tr_nums_2"><p><?php echo $_row_browser['IPNum']?></p></td>
		          <td class="tr_nums_3"><p><?php echo $_row_browser['TotalNum']?></p></td>
		        </tr>
		        <?php }?>
			</table>
		</div><!-- browser_detail_end -->

	</div><!-- browser_center -->

	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>