<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义一个常量用来引入文件
define('SCRIPT','visitspace');

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

	<div class="visitspace_center">
		<?php
		require ROOT_PATH.'includes/left_nav.inc.php';
		?>
		<div class="trend_cent_top">
	      <p>受访分析-受访空间</p>
	    </div>
	    <?php
	     require ROOT_PATH.'includes/total.inc.php';
	    ?>

<?php
$_space_sql  = "select SpaceUid,VisitNum from spaceclicks where Time  = (select Time from spaceclicks group by Time order by Time desc limit 1) order by VisitNum desc limit 10";
$_space_result = _query($_space_sql);
$_arr_space = array();
while (!!$_row = _fetch_array_list($_space_result)) {
	$_arr_space['SpaceUid'][] = $_row['SpaceUid'];
	$_arr_space['VisitNum'][] = $_row['VisitNum'];
}

$_SpaceUid = json_encode($_arr_space['SpaceUid']);
$_VisitNum = json_encode($_arr_space['VisitNum']);
?>

  <script src="chartjs/echarts.js"></script>
    <script type="text/javascript">
    require.config({
        paths: {
            echarts: './chartjs'
        }
    });
    
    // Step:4 require echarts and use it in the callback.
    // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
    require(
        [
            'echarts',
            'echarts/chart/bar'
        ],
        function (ec) {
            //--- 折柱 ---
            var myChart = ec.init(document.getElementById('space_bar'));
            myChart.setOption(
{
    title: {
        text: '个人空间访问量统计（仅统计到Uid）',
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
            data :<?php echo $_SpaceUid;?>
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
            data:<?php echo $_VisitNum;?>
        }
    ]
}
            	);     
        }
    );
    </script>

    <div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：下面柱形图显示的是空间访问量前十名的情况，根据日志文件只能够得到空间的UID。</p>
    </div>

		<div id="space_bar"></div>

    <div class="module_cent">
      <img src="image/yy.png"><p>温馨提示：下表显示空间访问的详细情况。（按照点击量排序）</p>
    </div>
    
		<div class="trend_w">
				<p class="bb_w">查看:</p>
				<p class="bb_t">访问空间详情</p>
		</div>


<?php
global $_pagesize,$_pagenum;
//第一个参数是获取总条数，第二个参数是每页多少条
_page("select SpaceUid,VisitNum,Time from spaceclicks",30);

$_spaceclick_sql = "select SpaceUid,VisitNum,Time from spaceclicks where Time = (select Time from visitpagestatistics  group by Time order by Time limit 1) order by VisitNum desc limit $_pagenum,$_pagesize";
$_result = _query($_spaceclick_sql);
?>


<div class="visitspace_detail">
			<table border="0"cellspacing="0" cellpadding="0">
				<tr>
					<td class="tr_num1"><p>空间链接</p></td>
					<td class="tr_num4"><p>用户UID</p></td>
					<td class="tr_num2"><p>点击量</p></td>
					<td class="tr_num3"><p>访问日期</p></td>
				</tr>
				<?php while(!!$_rows = _fetch_array_list($_result)) {?>
			<tr class="tb_row" onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this)">
		         <td class="tr_nums_1"><p><a href="<?php echo 'http://www.aboutyun.com/space-uid-'.$_rows['SpaceUid'].'.html'?>">><?php echo 'http://www.aboutyun.com/space-uid-'.$_rows['SpaceUid'].'.html'?></a></p></td>
		          <td class="tr_nums_4"><p><?php echo $_rows['SpaceUid'];?></p></td>
		          <td class="tr_nums_2"><p><?php echo $_rows['VisitNum'];?></p></td>
		          <td class="tr_nums_3"><p><?php echo $_rows['Time'];?></p></td>
		    </tr>
<?php }?>
		</table>

<?php
//_paging()函数调用分页 1|2 1表示数字分页2表示文本分页
_paging(2);
?>
		</div><!-- visitpage_detail_end -->

	</div><!-- visitspace_center_end -->
	<?php
	require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>