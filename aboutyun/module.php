<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','module');

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
	<?php 
	require ROOT_PATH.'includes/header.inc.php';
	?>
	<div class="module_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>受访分析-热点版块</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>
<?php
$_module_pie_sql = "select gid,gidname,TotalvisitNum,time from v_moduleclicks where time = (select time from v_moduleclicks  group by time order by time limit 1)";
$_pie_result=_query($_module_pie_sql);
$_arr_module_pie = array();
$_arr_module_piegidname= array();
$i=0;
while (!!$_row_pie=_fetch_array_list($_pie_result)) {
	$_arr_module_pie[$i]['value']=$_row_pie['TotalvisitNum'];
	$_arr_module_pie[$i]['name']=$_row_pie['gidname'];
	$i++;
	$_arr_module_piegidname['gidname'][]=$_row_pie['gidname'];
}
$_module_pie_data1=json_encode($_arr_module_pie);
$_module_pie_data2=json_encode($_arr_module_piegidname);

$_module_bar_sql = "select gid,gidname,TotalvisitNum,time from v_moduleclicks order by gid,time,TotalvisitNum limit 42";
$_bar_result=_query($_module_bar_sql);
$_arr_module_bar = array();
$_arr_module_bargidname= array();
$_arr_module_bartime= array();

for ($i=0; (!!$_row_bar=_fetch_array_list($_bar_result))==1; $i++) { 
	$ii=$i/7;
	$_arr_module_bar[$ii][]=$_row_bar['TotalvisitNum'];
	 if($ii<7){
		$_arr_module_bargidname[$ii]=$_row_bar['gidname'];
	 }	
	$_arr_module_bartime[$ii][]=$_row_bar['time'];
}
$_mod_data=array();
for ($i=0; $i < 6; $i++) { 
	$_mod_data[$i]=json_encode($_arr_module_bar[$i]);
}
$_module_name_data=json_encode($_arr_module_bargidname);
$_module_time_data=json_encode($_arr_module_bartime[0]);
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
               var myChartbar = ec.init(document.getElementById('module_bar'));
            myChartbar.setOption({
            	 
					    tooltip : {
					        trigger: 'axis',
					        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
					        }
					    },
					    legend: {
					        data:<?php echo $_module_name_data;?>
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
					            type : 'value'
					        }
					    ],
					    yAxis : [
					        {
					            type : 'category',
					            data : <?php echo $_module_time_data;?>
					        }
					    ],
					    series : [
					    	{
					            name:'云技术学习',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[0];?>
					        },
					        {
					            name:'解答讨论区',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[1];?>
					        },
					         {
					            name:'云开放平台',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[2];?>
					        },
					        {
					            name:'灵云（人工智能）',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[3];?>
					        },
					         {
					            name:'论坛站务',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[4];?>
					        },
					        {
					            name:'资源分享区',
					            type:'bar',
					            stack: '总量',
					            itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
					            data:<?php echo $_mod_data[5];?>
					        }
					        
					       
					    ]
					}
					            );

              



var myChartpie = ec.init(document.getElementById('module2_gra'));
            myChartpie.setOption( {
    title : {
        text: 'About云论坛各个主版块每天点击量统计',
        subtext: '纯属虚构',
        x:'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        x : 'center',
        y : 'bottom',
        data:<?php echo $_module_pie_data2;?>    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                type: ['pie', 'funnel']
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    series : [
        {
            name:'半径模式',
            type:'pie',
            radius : [20, 110],
            center : ['25%', 200],
            roseType : 'radius',
            width: '40%',       // for funnel
            max: 40,            // for funnel
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                },
                emphasis : {
                    label : {
                        show : true
                    },
                    labelLine : {
                        show : true
                    }
                }
            },
            data:<?php echo $_module_pie_data1;?>
        },
        {
            name:'面积模式',
            type:'pie',
            radius : [30, 110],
            center : ['75%', 200],
            roseType : 'area',
            x: '50%',               // for funnel
            max: 40,                // for funnel
            sort : 'ascending',     // for funnel
            data:<?php echo $_module_pie_data1;?>
        }
    ]
}
            );


            }
      );

	   </script> 

<div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：下表饼状图主要表示的是论坛各个版块的访问量占比！</p>
    </div>
    
<div id="module2_gra"></div>


<div class="module_cent">
	<img src="image/yy.png"><p>温馨提示：下面柱状图主要显示的是近七天论坛各个版块的访问量统计情况！</p>
</div>

	<div id="module_bar">
		
	</div>

	


	</div><!-- module_center_end -->


	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>