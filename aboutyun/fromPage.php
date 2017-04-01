<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','fromPage');

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
	<div class="fromHost_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>

		<div class="trend_cent_top">
	      <p>流量分析-来路页面分析</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>

<?php
$_frompage_pie_sql = "select FromPage,sum(TotalNum) as total from frompagestatistics 
where date = (select date from frompagestatistics  group by date order by date limit 1)
group by FromPage order by total desc limit 5";

$_pie_result = _query($_frompage_pie_sql);
$_arr_pie = array();
$_arr_frompage= array();
$i=0;
while (!!$_pie_row = _fetch_array_list($_pie_result)) {
  $_arr_pie[$i]['value']=$_pie_row['total'];
  $_arr_pie[$i]['name']=_url_judge(_url_len($_pie_row['FromPage'],35));
  $i++;
  $_arr_frompage['FromPage'][]=_url_judge(_url_len($_pie_row['FromPage'],35));
}
$_pie_data1 = json_encode($_arr_pie);
$_pie_data2 = json_encode($_arr_frompage['FromPage']);



$_frompage_sql_line = "select sum(FromNum) as U_from,sum(IPNum) as U_IP,sum(TotalNum) as U_Total,hours from frompagestatistics where date = (select date from frompagestatistics  group by date order by date limit 1) group by hours order by hours";
$_line_result = _query($_frompage_sql_line);
$_line_arr= array();
while (!!$_line_row=_fetch_array_list($_line_result)) {
  $_line_arr['FromNum'][] = $_line_row['U_from'];
  $_line_arr['IPNum'][] = $_line_row['U_IP'];
  $_line_arr['TotalNum'][] = $_line_row['U_Total'];
}

$_line_r1 = json_encode($_line_arr['FromNum']);
$_line_r2 = json_encode($_line_arr['IPNum']);
$_line_r3 = json_encode($_line_arr['TotalNum']);


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
                var myChartpie = ec.init(document.getElementById('fromurl_pie'));
                var optionpie = {
                          title : {
                                text: '来路页面链接前5名统计',
                                subtext: '大数据www.anoutyun.com',
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
                                    // data:[
                                    //     {value:335, name:'图文阅读'},
                                    //     {value:310, name:'导读'},
                                    //     {value:234, name:'博客'},
                                    //     {value:135, name:'排行榜'},
                                    //     {value:548, name:'分享'}
                                        
                                    // ]
                                }
                            ]
                        };
                    

               // 为echarts对象加载数据 
                myChartpie.setOption(optionpie); 
//--折线图--
               // 基于准备好的dom，初始化echarts图表
                var myChartline = ec.init(document.getElementById('fromurl_line')); 
                
               var optionline = {
                 
                   tooltip : {
                       trigger: 'axis'
                   },
                   legend: {
                       data:['来源页面统计','IP数统计','来源页面总量']
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
                           name:'来源页面统计',
                           type:'line',
                           // stack: '总量',
                           data:<?php echo $_line_r1;?>
                       },
                       {
                           name:'IP数统计',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_line_r2;?>
                       },
                       {
                           name:'来源页面总量',
                           type:'line',
                           //stack: '总量',
                           data:<?php echo $_line_r3;?>
                       }
                   ]
               };
               // 为echarts对象加载数据 
                myChartline.setOption(optionline); 

            }
      );

	   </script> 
	<div class="fromurl_graph">
    <div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：折线图统计的是网站每天来路页面的来访次数、IP数、以及站内的总访问次数。（单位：/小时）</p>
    </div>
    <div id="fromurl_line"></div><!-- fromurl_line_end -->
    <div class="module_cent">
    <img src="image/yy.png"><p>温馨提示：饼状图统计的是来访页面的前五名所占比例。（统计数据：来访次数）</p>
  </div>
		<div id="fromurl_pie"></div><!-- fromurl_pie_end -->	
	</div><!-- fromurl_graph -->
	

<?php
global $_pagesize,$_pagenum;
//第一个参数是获取总条数，第二个参数是每页多少条
_page("select FromPage,sum(FromNum) as T_fromnum,sum(IPNum) as T_ipnum,sum(TotalNum) as T_totalnum from frompagestatistics where date = (select date from frompagestatistics  group by date order by date limit 1) group by FromPage",30);

$_frompage_sql = "select FromPage,sum(FromNum) as T_fromnum,sum(IPNum) as T_ipnum,sum(TotalNum) as T_totalnum from frompagestatistics where date = (select date from frompagestatistics  group by date order by date limit 1) group by FromPage order by T_totalnum desc limit $_pagenum,$_pagesize";
$_result = _query($_frompage_sql);
?>

  <div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：以下表格显示的是来访页面的详细访问情况统计！</p>
  </div>

	<div class="trend_w">
		<p class="bb_w">查看:</p>
		<p class="bb_t">来路页面</p>
	</div>

	<div class="fromurl_detail">
		<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_num1"><p>来路页面</p></td>
					<td class="tr_num4"><p>来访次数</p></td>
					<td class="tr_num2"><p>IP数</p></td>
					<td class="tr_num3"><p>站内总访问次数</p></td>
				</tr>
<?php while(!!$_row = _fetch_array_list($_result)) {?>
				<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
		          <td class="tr_nums_1"><p><a href="">><?php echo _url_judge($_row['FromPage']);?></a></p></td>
		          <td class="tr_nums_4"><p><?php echo $_row['T_fromnum'];?></p></td>
		          <td class="tr_nums_2"><p><?php echo $_row['T_ipnum'];?></p></td>
		          <td class="tr_nums_3"><p><?php echo $_row['T_totalnum'];?></p></td>
		        </tr>
<?php }?>
		</table>
    <?php
      //_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
      _paging(2);
    ?>
	</div><!-- fromurl_detail_end -->

	</div><!-- fromHost_center_end -->

	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>